#!/usr/bin/env python3
"""Generate a PDF invoice from a JSON file.

Usage:  python3 generate_invoice.py order.json [output.pdf]
Needs:  pip install jinja2 playwright   (and a Chromium for Playwright)
"""
import json
import sys
from pathlib import Path

from jinja2 import Environment, FileSystemLoader
from playwright.sync_api import sync_playwright

HERE = Path(__file__).resolve().parent

COMPANY = {
    "name": "Tech Solutions Repair Service",
    "legal_name": "Tech Solutions Repair Service Inc.",
    "address": "51 Majestic Ct, Apt 904, Moorpark, CA 93021",  # matches the BHGS registration; required on invoices
    "service_area": "Serving Moorpark & Ventura County",
    "phone": "(805) 334-8375",
    "email": "",  # set your business email here (shown on the invoice if not empty)
    "website": "tsrepairservice.com",
    "registration": "State Registration No. A 50537 (BHGS)",
}

SERVICE_CALL_FEE = 95.00   # service call / diagnostic fee (must be disclosed in writing before work starts)
ESTIMATE_VALID_BUSINESS_DAYS = 5
# Standard guarantee (matches the BHGS implied guarantee: 30 days labor, 90 days parts).
# Have BHGS/your lawyer confirm the wording before first use.
DEFAULT_WARRANTY = ("Guarantor: Tech Solutions Repair Service Inc. Labor is guaranteed for 30 days and parts "
                    "installed for 90 days from the date of service. The guarantee covers the repair performed and "
                    "the parts installed; it does not cover other components or damage caused by misuse.")

DEFAULT_PAYMENT_METHODS = "Cash, check, Zelle or card (confirm with technician)."
# California sales tax applies to parts, not to repair labor. Ventura County base rate
# is 7.25% (some cities add district tax) - confirm the rate for your jobs.
DEFAULT_TAX_RATE = 0.0725
# Tax is OFF until the business has a CDTFA seller's permit. Then set "charge_tax": true
# in the order JSON (or flip this default) and add the permit number to COMPANY.
CHARGE_TAX_DEFAULT = False


def money(value):
    return f"${value:,.2f}"


def build_context(order):
    labor_items = order.get("labor_items", [])
    part_items = order.get("part_items", [])
    tax_rate = order.get("tax_rate", DEFAULT_TAX_RATE)

    labor = round(sum(i["qty"] * i["unit"] for i in labor_items), 2)
    parts = round(sum(i["qty"] * i["unit"] for i in part_items), 2)
    charge_tax = order.get("charge_tax", CHARGE_TAX_DEFAULT)
    tax = round(parts * tax_rate, 2) if charge_tax else 0.0
    discount = round(order.get("discount", 0), 2)
    total = round(labor + parts + tax - discount, 2)
    paid = round(order.get("paid", 0), 2)
    balance = round(total - paid, 2)

    company = dict(COMPANY)
    if not company["address"] and order["invoice"].get("sample"):
        company["address"] = "[Business address required]"
    return {
        "company": company,
        "used_parts_statement": order.get("used_parts_statement", "No used or exchanged parts were installed."),
        "estimate_ref": order.get("estimate_ref", ""),
        "invoice": order["invoice"],
        "customer": order["customer"],
        "appliance": order["appliance"],
        "labor_items": labor_items,
        "part_items": part_items,
        "warranty": order.get("warranty", DEFAULT_WARRANTY),
        "notes": order.get("notes", ""),
        "payment": {
            "methods": order.get("payment_methods", DEFAULT_PAYMENT_METHODS),
            "method_used": order.get("payment_method_used", ""),
        },
        "tax_rate_label": f"{tax_rate * 100:g}%",
        "totals": {"show_tax": charge_tax, "labor": labor, "parts": parts, "tax": tax, "discount": discount,
                   "total": total, "paid": paid, "balance": balance},
        "money": money,
    }


def main():
    if len(sys.argv) < 2:
        sys.exit(__doc__)
    order = json.loads(Path(sys.argv[1]).read_text(encoding="utf-8"))
    out = Path(sys.argv[2]) if len(sys.argv) > 2 else HERE / f"invoice_{order['invoice']['number']}.pdf"

    env = Environment(loader=FileSystemLoader(HERE), autoescape=True)
    html = env.get_template("template.html").render(**build_context(order))
    html_path = HERE / "_rendered.html"   # saved next to logo.png so the image resolves
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
