import requests

response = requests.get("https://admin.placbillstrack.org/api/bills?page=11")
print(response.json())  # Check the response format
