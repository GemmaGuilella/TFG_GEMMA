#   La raspberry llegirà el codi QR
#   del mobil del usuari
#   Farà un decoder, i tindrà un text codificat
#   La passarà al Laravel
#   Laravel descodificarà el text

from pyzbar.pyzbar import decode
from PIL import Image

img = Image.open('qr.png')
result = decode(img)
for i in result:
    print(i.data.decode("utf-8"))