import os
import json
from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    # Capture console logs
    page.on("console", lambda msg: print(f"Console: {msg.text}"))
    page.on("pageerror", lambda exc: print(f"PageError: {exc}"))

    # Read dummy data
    with open("public/assets/data/dummy-wind-data.json", "r") as f:
        wind_data = json.load(f)

    # Route API call
    def handle_route(route):
        print(f"Intercepted: {route.request.url}")
        route.fulfill(status=200, body=json.dumps(wind_data), content_type="application/json")

    page.route("**/api/wind-map-data", handle_route)

    # Load page
    cwd = os.getcwd()
    url = f"file://{cwd}/verification/index.html"
    print(f"Loading {url}")
    page.goto(url)

    # Wait for map initialization and data load
    print("Waiting for map to render...")
    page.wait_for_timeout(5000)

    # Screenshot
    print("Taking screenshot...")
    page.screenshot(path="verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
