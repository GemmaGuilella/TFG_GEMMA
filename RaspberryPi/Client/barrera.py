import cv2
import requests
from enum import Enum
from time import sleep
import pyzbar.pyzbar as zbar


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
        """
        print("E0")
        self.__state = State.E1

    def __e1(self):
        """
        """
        print("E1")
        self.__state = State.E2

    def __e2(self):
        """
        """
        print("E2")
        self.__state = State.E3

    def __e3(self):
        """
        """
        print("E3")
        self.__state = State.E4

    def __e4(self):
        """
        """
        print("E4")
        self.__state = State.E5

    def __e5(self):
        """
        """
        print("E5")
        self.__state = State.E6

    def __e6(self):
        """
        """
        print("E6")
        self.__state = State.E0

    def execute(self):
        while True:
            match self.__state:
                case State.E0: self.__e0()
                case State.E1: self.__e1()
                case State.E2: self.__e2()
                case State.E3: self.__e3()
                case State.E4: self.__e4()
                case State.E5: self.__e5()
                case State.E6: self.__e6()


if __name__ == '__main__':
    m = Machine()
    m.execute()
