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
        self.__qr: str = ""
        self.__respostaHTTP: int = 0

    def __e0(self):
        """
        Estat 0
        """
        print("E0")
        GPIO.output(Blue, False)
        GPIO.output(Green, False)
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
                print("QR ->", self.__QR)
                if barcode_info:
                    ret = False
            cv2.imshow('Parking_token ->', frame)
            if cv2.waitKey(1) & 0xFF == 27:
                break

        camera.release()
        cv2.destroyAllWindows()
        GPIO.output(Bzz, True)
        sleep(0.5)
        self.__state = State.E1

    def __e1(self):
        """
        Estat 1
        """
        print("E1")
        GPIO.output(Bzz, False)
        GPIO.output(Red, False)
        GPIO.output(Blue, True)
        #parking = requests.post("http://127.0.0.1:8000/api/barriers/open", { "qr": self.__QR })
        parking = requests.post(
            "http://192.168.1.33:8000/api/barriers/open", {"qr": self.__QR})
        self.__respostaHTTP = parking.status_code
        print("setRespostaHTTP", self.__respostaHTTP)
        self.__state = State.E2

    def __e2(self):
        """
        """
        print("E2")
        GPIO.output(Blue, False)
        if (self.__respostaHTTP == 204):
            self.__state = State.E4
        else:
            GPIO.output(Red, True)
            self.__state = State.E3

    def __e3(self):
        """
        """
        print("E3")
        for x in range(0, 4):
            GPIO.output(Red, True)
            sleep(0.2)
            GPIO.output(Red, False)
            sleep(0.2)
        self.__state = State.E0

    def __e4(self):
        """
        """
        print("E4")
        GPIO.output(Green, True)
        print("puja barrera")
        for x in range(0, 4):
            GPIO.output(Green, True)
            sleep(0.3)
            GPIO.output(Green, False)
            sleep(0.3)
        self.__state = State.E5

    def __e5(self):
        """
        """
        print("E5")
        print("barrera pujada, pot passar")
        GPIO.output(Green, True)
        sleep(4)
        self.__state = State.E6

    def __e6(self):
        """
        """
        print("E6")
        print("baixa la barrera")
        for x in range(0, 4):
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
