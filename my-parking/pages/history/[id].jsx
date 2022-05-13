import axios from 'axios'
import Link from 'next/link'
import { useRouter } from 'next/router'
import React, { useState, useEffect, useMemo, useCallback } from 'react'
import Card from '../../components/card'
import TitleMenu from '../../components/titleMenu'
import Clock from '../../components/icons/clock'
import Eur from '../../components/icons/eur'
import Time from '../../components/icons/time'
import Explication from '../../components/explication'
import Button from '../../components/button'
import ArrowRight from '../../components/icons/arrowRight'
import ArrowLeft from '../../components/icons/arrowLeft'
import useSWR from 'swr'
import Location from '../../components/icons/location'
import dayjs from 'dayjs'
import { auth } from '../../context/user'
import CalenderToday from '../../components/icons/calenderToday'
import { successAlert, errorAlert } from '../../lib/alerts'

function History() {
  const router = useRouter()
  const { id } = router.query
  const { data: car, mutate } = useSWR(() => (id ? `/cars/${id}` : false))
  const { data: settings } = useSWR(`/settings`)

  const [errors, setErrors] = useState(undefined)
  const now = dayjs()

  const [preu, setPreu] = useState(0)

  const preuHora = useMemo(() => {
    if (!settings) return 0
    if (settings.data.price_hour === 0) return 1
    return (Math.ceil(settings.data.price_hour) / 100).toLocaleString('es', {
      useGrouping: false,
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })
  })

  const preuTotalEuros = useMemo(() => {
    if (preu === 0) return 0
    return (preu / 100).toLocaleString('es', {
      useGrouping: false,
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })
  }, [preu])

  useEffect(() => {
    function handler() {
      const now = dayjs()

      if (car && settings && car.data.space) {
        const priceMinute = settings.data.price_hour / 60
        setPreu(priceMinute * now.diff(dayjs(car.data.space.updated_at), 'minutes'))
      }
    }
    handler()
    const interval = setInterval(handler, 1000)
    return () => clearInterval(interval)
  }, [car, settings])

  const sortir = useCallback(
    (e) => {
      e.preventDefault()
      setErrors(undefined)
      axios
        .post('/checkout/' + car.data.id, {
          success_url: new URL(
            `/payments/success/${car.data.id}`,
            window.location.protocol + window.location.hostname + ':' + window.location.port
          ).toString(),
          cancel_url: new URL(
            `/payments/cancel/${car.data.id}`,
            window.location.protocol + window.location.hostname + ':' + window.location.port
          ).toString(),
        })
        .then((res) => router.push(res.data.data.url))
        .catch(function (err) {
          setErrors(err.response.data?.message)
          errorAlert({ text: err.response.data?.message ?? 'Error desconegut' })
        })
    },
    [car, router]
  )

  if (car && car.data.is_parked === false) {
    router.push('/')
    return null
  }

  return (
    <>
      <TitleMenu>Informació!</TitleMenu>
      {car && car.data.is_parked && car.data.space && (
        <div>
          <Card>
            <div className="flex flex-row justify-between my-6 space-x-12 font-medium">
              <div className="flex flex-row space-x-6">
                <Location />
                <div>Plaça Associada</div>
              </div>
              <div>{car.data.space.id}</div>
            </div>
            <div className="flex flex-row justify-between my-6 space-x-12 font-medium">
              <div className="flex flex-row space-x-6">
                <CalenderToday />
                <div>Dia Entrada</div>
              </div>

              <div>{dayjs(car.data.space.updated_at).format('DD/MM/YYYY')}</div>
            </div>
            <div className="flex flex-row justify-between my-6 space-x-12 font-medium">
              <div className="flex flex-row space-x-6">
                <Clock />
                <div>Hora Entrada</div>
              </div>

              <div>{dayjs(car.data.space.updated_at).format('HH:mm')}</div>
            </div>
            <div className="flex flex-row justify-between my-6 space-x-12 font-medium">
              <div className="flex flex-row space-x-6">
                <Eur />
                <div>Preu hora</div>
              </div>
              <div>{preuHora}€/h</div>
            </div>
            <div className="flex flex-row justify-between my-6 space-x-12 font-medium">
              <div className="flex flex-row space-x-6">
                <CalenderToday />
                <div>Dia actual</div>
              </div>

              <div>{now.format('DD/MM/YYYY')}</div>
            </div>
            <div className="flex flex-row justify-between my-6 space-x-12 font-medium">
              <div className="flex flex-row space-x-6">
                <Time />
                <div>Temps actual</div>
              </div>
              <div>{now.format('HH:mm')}</div>
            </div>

            <div className="flex flex-row justify-between my-6 space-x-12 font-medium">
              <div className="flex flex-row space-x-6">
                <Eur />
                <div>Preu total</div>
              </div>
              <div>{preuTotalEuros}€</div>
            </div>
          </Card>
          <Explication>
            <span>
              1. Per sortir, premeu el botó de <span className="font-semibold">Sortir</span>.
            </span>
            <span>2. Se us redirigirà a la pantalla de pagament.</span>
          </Explication>
          <div className="flex flex-row justify-end m-6 md:mx-auto md:w-1/2">
            <form onSubmit={sortir}>
              <Button>
                <div className="flex items-center space-x-2 text-sm tracking-wider">
                  <a>Sortir</a>
                  <ArrowRight />
                </div>
              </Button>
            </form>
          </div>
        </div>
      )}
    </>
  )
}

export default auth(History)
