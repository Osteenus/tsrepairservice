"""Check indexable HTML without JavaScript. Usage: python3 scripts/check-seo.py BASE_URL"""
import json
import sys
import urllib.request
import xml.etree.ElementTree as ET
from html.parser import HTMLParser

base = sys.argv[1].rstrip('/')
class Page(HTMLParser):
    def __init__(self):
        super().__init__()
        self.h1 = 0
        self.canonical = []
        self.description = []
        self.schemas = []
        self.schema = None
        self.in_head = False
        self.in_title = False
        self.title = ''
    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if tag == 'head': self.in_head = True
        if tag == 'title' and self.in_head: self.in_title = True
        if tag == 'h1': self.h1 += 1
        if tag == 'link' and a.get('rel') == 'canonical': self.canonical.append(a['href'])
        if tag == 'meta' and a.get('name') == 'description': self.description.append(a['content'])
        if tag == 'meta' and a.get('name') == 'robots': assert 'noindex' not in a.get('content', '')
        if tag == 'script' and a.get('type') == 'application/ld+json': self.schema = ''
    def handle_data(self, data):
        if self.in_title: self.title += data
        if self.schema is not None: self.schema += data
    def handle_endtag(self, tag):
        if tag == 'head': self.in_head = False
        if tag == 'title': self.in_title = False
        if tag == 'script' and self.schema is not None:
            self.schemas.append(json.loads(self.schema))
            self.schema = None

def read(url):
    with urllib.request.urlopen(url, timeout=30) as response:
        assert response.status == 200
        assert 'noindex' not in response.headers.get('X-Robots-Tag', '')
        return response.read().decode()

urls = [e.text for e in ET.fromstring(read(base + '/sitemap.xml')).iter('{http://www.sitemaps.org/schemas/sitemap/0.9}loc')]
titles = set()
for canonical in urls:
    path = canonical.replace('https://tsrepairservice.com', '')
    page = Page(); page.feed(read(base + path))
    assert page.h1 == 1, (path, 'missing or duplicate H1')
    assert page.canonical == [canonical], (path, 'canonical')
    assert len(page.description) == 1 and page.description[0], (path, 'description')
    assert page.title and page.title not in titles, (path, 'title')
    titles.add(page.title)
    print('PASS', path, 'HTML content, metadata, JSON-LD:', len(page.schemas))
print(f'{len(urls)} pages passed without JavaScript')
