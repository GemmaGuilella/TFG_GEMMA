# from tokenize import String
import requests
# from pyzbar.pyzbar import decode
# from PIL import Image

# img = Image.open('qr.png')
# result = decode(img)
# for i in result:
#     print(i.data.decode("utf-8"))
parking = requests.post(
    "http://127.0.0.1:8000/api/barriers/open", {"qr": "eyJpdiI6IldmRy80MWpJUHZ3cUIvSktMZmtoYVE9PSIsInZhbHVlIjoia2tBMFlmZGJHYiswZ05oV0dMc0tSQnV6RkdmM01nMlpzeTBndytMWHU1cnY4YlRIMW9mR1l4T3NyT2RSamR5RnZhNE95Uk1CR3J3K2tRZ3UxL3NWV0ErVUZRYXh6eTgvLzBBOWVmZjE0VlhZU1pQcnFnLzhyWE9mbFhFSDJ1YTkiLCJtYWMiOiJlMWU5YWFmMGIzMjBlNjcyY2M0ZmQ1Y2Q3NmRjOTBkMzI4Y2M0OTM5MjI1MmM5ZDllOTY0ODE1MjI1MDc1ZGFhIiwidGFnIjoiIn0"})

# print(parking.url)
# print(parking.text)
print(parking.status_code)
# print(parking.json)
