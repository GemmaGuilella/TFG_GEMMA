import cv2
import requests
from time import sleep
import pyzbar.pyzbar as zbar


class Barrera:
    """
    Classe barrera.
    """

    def __init__(self) -> None:
        """
        Inicialitza la classe Barrera
        """
        self.state: int = 0
        self.__qr: str = ""
        self.__respostaHTTP: int = 0

    def setState(self, nouEstat):
        """
        Mètode que guarda un nou valor a l'atribut self.estat
        """
        self.state = nouEstat

    def setQR(self, nouQR):
        """
        Mètode que guarda un nou valor a l'atribut self.__QR
        """
        self.__qr = nouQR

    def setRespostaHTTP(self, novaTespostaHTTP):
        """
        Mètode que guarda un nou valor a l'atribut self.__respostaHTTP
        """
        self.__respostaHTTP = novaTespostaHTTP

    def estats(self):
        """
        Mira l'estat amb que es troba el programa
        i realitza el que ha de fer depenent d'ell

        estatInicial => obrir càmera i llegir QR
        estat 1 => Petició HTTP
        """
        return self.obrirCamera() if self.state == 0  else self.peticioHTTP()

    def peticioHTTP(self):
        """
        Aquesta funció farà la petició HTTP al servidor del Laravel passant-li el valor del QR que s'ha llegit.
        Enendrà un LED de color Blau per mostrar que ha fet la petició.
        Si la resposta HTTP del servidor és 204 la barrera s'obrirà, si no es mantindrà tancada.
        """
        print("estat 1")
        parking = requests.post("http://192.168.1.39:8000/api/barriers/open", { "qr": self.__qr })
        self.setRespostaHTTP(parking.status_code)
        print("LED BLAU")
        print(self.__respostaHTTP)
        if (self.__respostaHTTP == 204):
            print("LED VERD")
            sleep(3)
            print("LED VERMELL")
        else:
            print("LED VERMELL")
            print("No ha anat bé")
        self.setState(0)
        self.estats()


    def obrirCamera(self):
        """
        Obra la càmera per tal de llegir el codi QR
        Guardar aquesta variable.
        """
        print("Estat 0")
        camera = cv2.VideoCapture(0)
        delay = 1

        while(True):
            ret = cv2.waitKey(delay) & 0xFF
            # Capture frame-by-frame
            ret, frame = camera.read()

            if cv2.waitKey(1) & 0xFF == 27:
                break

            decodedObjects = zbar.decode(frame)
            for i in decodedObjects:
                self.setQR(i.data.decode("utf-8"))
                print("Bzz")
                self.setState(2)
                self.estats()

            # Display the resulting frame
            cv2.imshow('frame',frame)

        # When everything done, release the capture
        camera.release()
        cv2.destroyAllWindows()



if __name__ == '__main__':
    b = Barrera()
    b.estats()