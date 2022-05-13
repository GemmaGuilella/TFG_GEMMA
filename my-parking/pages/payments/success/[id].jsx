import React from 'react'
import Card from '../../../components/card'
import Ok from '../../../components/icons/ok'
import Button from '../../../components/button'
import { auth } from '../../../context/user'
import Link from 'next/link'
import { useRouter } from 'next/router'
import QRCode from 'react-qr-code'
import useSWR from 'swr'

function succes() {
  const router = useRouter()
  const { id } = router.query
  const { data: car, mutate } = useSWR(() => (id ? `/cars/${id}` : false))
  const { data: settings } = useSWR('/settings')
  return (
    <div>
      <Card>
        <div className="flex flex-col items-center justify-center space-y-6">
          <Ok className="w-32 h-32 text-green-500" />
          {car && car.data.qr_token && <QRCode size={280} value={car.data.qr_token} level="L" />}
          <p className="text-justify">
            Un cop fet el pagamant, es genera un codi QR per poder sortir del pàrquing. Disposes de{' '}
            {settings?.data.token_expiration} minuts per fer-ho.
          </p>
          <Button>
            <Link href="/">Index</Link>
          </Button>
        </div>
      </Card>
    </div>
  )
}

export default auth(succes)
