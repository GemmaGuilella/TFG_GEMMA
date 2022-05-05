# Programa de prova
# Realment qui fa els codis QRs és l'APP, ella rebrà un text codificat per Laravel
# I farà el QR!

import qrcode
from PIL import Image

qr = qrcode.QRCode(
    version=2,
    error_correction=qrcode.constants.ERROR_CORRECT_H,
    box_size=10,
    border=4,
)
qr.add_data('8118GSY')
qr.make(fit=True)
img = qr.make_image(fill_color="black", back_color="white").convert('RGB')
img.save("QR_Imatges/prova1.png")