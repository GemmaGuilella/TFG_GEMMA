import cv2
import requests
from time import sleep
from pyzbar import pyzbar

def state_machine(state, result):
    if (state == 0):
        print('Bzz -> 0')
        print('LedR-> 1')
        print('LedG-> 0')
        print('LedB-> 0')
    elif (state == 1):
        print('QR LLEGIT OK!')
        print('Bzz -> 1')
        print('LedR-> 1')
        print('LedG-> 0')
        print('LedB-> 0')
    elif (state == 2):
        if (result == 206):
            print('Bzz -> 0')
            print('LedR-> 0')
            print('LedG-> 0')
            print('LedB-> 1')
            sleep(200)
            print('Bzz -> 0')
            print('LedR-> 0')
            print('LedG-> 1')
            print('LedB-> 0')
        else:
            print('QR INVALID!')
            state = 0
            state_machine(state, None)
    return

def read_barcodes(frame):
    barcodes = pyzbar.decode(frame)
    for barcode in barcodes:
        x, y , w, h = barcode.rect
        #1
        barcode_info = barcode.data.decode('utf-8')
        # Aquí fer que el Bzz
        state = 1
        state_machine(state, result = None)
        cv2.rectangle(frame, (x, y),(x+w, y+h), (0, 255, 0), 2)

        #2
        font = cv2.FONT_HERSHEY_DUPLEX
        cv2.putText(frame, barcode_info, (x + 6, y - 6), font, 2.0, (255, 255, 255), 1)

        # No guardar en un fitxer! enviar-ho directament al server.
        with open("barcode_result.txt", mode ='w') as file:
            file.write(barcode_info)
        parking = requests.post("http://127.0.0.1:8000/api/barriers/open", { "qr": barcode_info })
        result = parking.status_code
        print(result)
        state = 2
        state_machine(state, result)
    return frame

def main():
    state_machine(0, result = None)
    camera = cv2.VideoCapture(0)
    ret, frame = camera.read()
    while ret:
        ret, frame = camera.read()
        frame = read_barcodes(frame)
        cv2.imshow('Parking_token ->', frame)
        if cv2.waitKey(1) & 0xFF == 27:
            break

    camera.release()
    cv2.destroyAllWindows()

if __name__ == '__main__':
    main()