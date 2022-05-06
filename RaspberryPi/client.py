import cv2
import requests
from enum import Enum
from time import sleep
import pyzbar.pyzbar as zbar
import RPi.GPIO as GPIO
from pyzbar import pyzbar


class State(Enum):
    E0 = 0
    E1 = 1
    E2 = 2
    E3 = 3
    E4 = 4
    E5 = 5
    E6 = 6


class Machine:

    def __init__(self):
        """
        Inicialitza la classe Machine
        """
        self.__state: State = State.E0
        self.__QR: str = ""
        self.__respostaHTTP: int = 0

    def __e0(self):
        """
        Obra la càmera i "busca" codis QR, quan n'ha trobat tanca la
        càmera i guarda el valor del codi QR.
        """
        GPIO.output(Blue, False)
        GPIO.output(Green, False)
        GPIO.output(Bzz, False)
        GPIO.output(Red, True)
        camera = cv2.VideoCapture(0)
        ret, frame = camera.read()
        while ret:
            ret, frame = camera.read()
            barcodes = pyzbar.decode(frame)
            for barcode in barcodes:
                x, y, w, h = barcode.rect
                barcode_info = barcode.data.decode('utf-8')
                self.__QR = barcode_info
                #print("QR ->", self.__QR)
                if barcode_info:
                    ret = False
            #cv2.imshow('Parking_token ->', frame)
            # if cv2.waitKey(1) & 0xFF == 27:
              # break

        camera.release()
        cv2.destroyAllWindows()
        GPIO.output(Bzz, True)
        sleep(0.5)
        self.__state = State.E1

    def __e1(self):
        """
        Fa la petició HTTP. Encèn el LED blau.
        """
        GPIO.output(Bzz, False)
        GPIO.output(Red, False)
        GPIO.output(Blue, True)
        try:
            parking = requests.post(
                "http://10.10.10.1:8000/api/barriers/open", {"qr": self.__QR})
        except requests.exceptions.HTTPError as err:
            raise SystemExit(err)
        self.__respostaHTTP = parking.status_code
        self.__state = State.E2

    def __e2(self):
        """
        Comprova la resposta de la petició HTTP.
        Depenent del seu resultat farà diferentes accions.
        """
        GPIO.output(Blue, False)
        if (self.__respostaHTTP == 204):
            self.__state = State.E4
        else:
            GPIO.output(Red, True)
            self.__state = State.E3

    def __e3(self):
        """
        Aquest estat indica quan la petició HTTP no ha sigut satisfactòria.
        """
        for x in range(0, 5):
            GPIO.output(Red, True)
            sleep(0.3)
            GPIO.output(Red, False)
            sleep(0.3)
        self.__state = State.E0

    def __e4(self):
        """
        Aquest estat simbolitza quan la barrera està pujant
        """
        for x in range(0, 20):
            GPIO.output(Green, True)
            sleep(0.3)
            GPIO.output(Green, False)
            sleep(0.3)
        self.__state = State.E5

    def __e5(self):
        """
        Estat que simbulitza la barrara pujada.
        """
        GPIO.output(Green, True)
        sleep(30)
        self.__state = State.E6

    def __e6(self):
        """
        Simbulitza que la barrera està baixant.
        """
        for x in range(0, 20):
            GPIO.output(Green, True)
            sleep(0.2)
            GPIO.output(Green, False)
            sleep(0.2)
        GPIO.output(Green, False)
        self.__state = State.E0

    def execute(self):
        while True:
            if self.__state == State.E0:
                self.__e0()
                continue
            if self.__state == State.E1:
                self.__e1()
                continue
            if self.__state == State.E2:
                self.__e2()
                continue
            if self.__state == State.E3:
                self.__e3()
                continue
            if self.__state == State.E4:
                self.__e4()
                continue
            if self.__state == State.E5:
                self.__e5()
                continue
            if self.__state == State.E6:
                self.__e6()
                continue


if __name__ == '__main__':
    Red = 18
    Blue = 23
    Green = 24
    Bzz = 25

    GPIO.setwarnings(False)
    GPIO.setmode(GPIO.BCM)
    GPIO.setup(Bzz, GPIO.OUT)
    GPIO.setup(Red, GPIO.OUT)
    GPIO.setup(Blue, GPIO.OUT)
    GPIO.setup(Green, GPIO.OUT)
    m = Machine()
    m.execute()
