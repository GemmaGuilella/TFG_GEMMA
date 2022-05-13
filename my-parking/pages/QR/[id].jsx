import useSWR from 'swr'
import { useEffect, useCallback } from 'react'
import Link from 'next/link'
import QRCode from 'react-qr-code'
import { useRouter } from 'next/router'
import Card from '../../components/card'
import { auth } from '../../context/user'
import TitleMenu from '../../components/titleMenu'
import Explication from '../../components/explication'
import ArrowLeft from '../../components/icons/arrowLeft'
import ArrowRight from '../../components/icons/arrowRight'
import classnames from 'classnames'
import axios from 'axios'
import Button from '../../components/button'

function qr() {
  const router = useRouter()
  const { id } = router.query
  const { data: car, mutate } = useSWR(() => (id ? `/cars/${id}` : false))
  const { data: settings } = useSWR(`/settings`)

  const createQr = useCallback(
    function () {
      if (id) {
        axios.get(`/cars/${id}/qr`).then(() => mutate())
      }
    },
    [id, mutate]
  )

  useEffect(createQr, [id])

  const qrCode = String(car && car.data.qr_token)
  return (
    <div>
      <TitleMenu>Codi QR!</TitleMenu>
      <Explication>
        <span>1. Escaneja el CODI QR per entrar al pàrquing</span>
        <span>2. Aquest codi serà vàlid durant {settings?.data.token_expiration} minuts.</span>
      </Explication>

      <Card>
        <div className="flex flex-col items-center justify-center space-y-4">
          <div
            className={classnames('h-[280px] transition-opacity duration-500', {
              'opacity-100': qrCode,
              'opacity-0': !qrCode,
            })}>
            {car && car.data.qr_token && <QRCode size={280} value={car.data.qr_token} level="L" />}
          </div>
          <Button onClick={createQr}>Generar un altre QR</Button>
        </div>
      </Card>
      <div className="flex flex-row justify-between m-6 md:mx-auto md:w-1/2">
        <Link href="/">
          <div className="flex items-center space-x-2 text-sm tracking-wider text-gray-600">
            <ArrowLeft />
            <a>Enrere</a>
          </div>
        </Link>
        <Link href={`/history/${id}`}>
          <button
            disabled={!car || (car && car.data.is_parked === false)}
            className="flex py-1.5 px-3 bg-gray-700 rounded-md text-white tracking-wider shadow disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-600">
            <div className="flex items-center space-x-2 text-sm tracking-wider">
              <a>Informació</a>
              <ArrowRight />
            </div>
          </button>
        </Link>
      </div>
    </div>
  )
}

export default auth(qr)
