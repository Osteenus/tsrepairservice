#!/usr/bin/env python3
"""Generate a PDF written estimate from a JSON file.

Usage:  python3 generate_estimate.py estimate.json [output.pdf]
Uses the same company details as generate_invoice.py.
"""
import json
import sys
from pathlib import Path

from jinja2 import Environment, FileSystemLoader
from playwright.sync_api import sync_playwright

import datetime as dt

from generate_invoice import COMPANY, DEFAULT_WARRANTY, ESTIMATE_VALID_BUSINESS_DAYS, SERVICE_CALL_FEE, money

HERE = Path(__file__).resolve().parent


def add_business_days(start, days):
    d = start
    while days > 0:
        d += dt.timedelta(days=1)
        if d.weekday() < 5:   # Mon-Fri (holidays are not excluded)
            days -= 1
    return d


def build_context(order):
    est = dict(order["estimate"])
    if not est.get("valid_until"):
        issued = dt.datetime.strptime(est["date"], "%b %d, %Y").date()
        until = add_business_days(issued, est.get("valid_business_days", ESTIMATE_VALID_BUSINESS_DAYS))
        est["valid_until"] = until.strftime("%b %d, %Y").replace(" 0", " ")

    labor_items = order.get("labor_items", [])
    part_items = order.get("part_items", [])
    labor = round(sum(i["qty"] * i["unit"] for i in labor_items), 2)
    parts = round(sum(i["qty"] * i["unit"] for i in part_items), 2)
    company = dict(COMPANY)
    if not company["address"] and order["estimate"].get("sample"):
        company["address"] = "[Business address required]"
    return {
        "company": company,
        "estimate": est,
        "customer": order["customer"],
        "appliance": order["appliance"],
        "labor_items": labor_items,
        "part_items": part_items,
        # BHGS: a diagnostic fee may be charged only if disclosed in writing BEFORE work starts.
        "diagnostic_terms": order.get("diagnostic_terms", f"The {money(SERVICE_CALL_FEE)} service call / diagnostic fee is included in the estimate total, not charged extra. If you decline the repair, only {money(SERVICE_CALL_FEE)} is due."),
        "warranty": order.get("warranty", DEFAULT_WARRANTY),
        "notes": order.get("notes", ""),
        "totals": {"labor": labor, "parts": parts, "total": round(labor + parts, 2)},
        "money": money,
    }


def main():
    if len(sys.argv) < 2:
        sys.exit(__doc__)
    order = json.loads(Path(sys.argv[1]).read_text(encoding="utf-8"))
    out = Path(sys.argv[2]) if len(sys.argv) > 2 else HERE / f"estimate_{order['estimate']['number']}.pdf"
    env = Environment(loader=FileSystemLoader(HERE), autoescape=True)
    html = env.get_template("estimate.html").render(**build_context(order))
    html_path = HERE / "_rendered_estimate.html"
    html_path.write_text(html, encoding="utf-8")
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()
        page.goto(html_path.as_uri())
        page.pdf(path=str(out), format="Letter", print_background=True,
                 margin={"top": "0", "right": "0", "bottom": "0", "left": "0"})
        browser.close()
    html_path.unlink(missing_ok=True)
    print(f"Saved {out}")


if __name__ == "__main__":
    main()
