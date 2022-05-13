import axios from 'axios'
import Card from '../../components/card'
import Link from 'next/link'
import TitleCard from '../../components/titleCard'
import TitleMenu from '../../components/titleMenu'
import { useState, useEffect, useCallback } from 'react'
import ArrowLeft from '../../components/icons/arrowLeft'
import useSWR, { useSWRConfig } from 'swr'
import { useRouter } from 'next/router'
import { admin } from '../../context/user'
import Input from '../../components/input'
import Button from '../../components/button'
import { successAlert, errorAlert } from '../../lib/alerts'
import dayjs from 'dayjs'

function Page() {
  const router = useRouter()
  const { data: settings, mutate } = useSWR(`/settings`)
  const { data: logs } = useSWR('/logs')
  const [preu, setPreu] = useState('')
  const [tokenExpiration, setTokenExpiration] = useState('')
  const [errors, setErrors] = useState(undefined)

  useEffect(
    function () {
      if (settings) {
        setPreu(settings.data.price_hour)
        setTokenExpiration(settings.data.token_expiration)
      }
    },
    [settings]
  )

  const changePreu = useCallback((event) => setPreu(event.currentTarget.value), [setPreu])
  const changeToken = useCallback(
    (event) => setTokenExpiration(event.currentTarget.value),
    [setTokenExpiration]
  )

  const saveSettings = useCallback(
    (event) => {
      event.preventDefault()
      setErrors(undefined)

      axios
        .put('/settings', {
          price_hour: parseInt(preu),
          token_expiration: parseInt(tokenExpiration),
        })
        .then(async function () {
          await mutate()
          successAlert({ text: "S'han actualitzat les configuracions del parking" })
        })
        .catch(function (err) {
          setErrors(err.response.data?.errors)
          errorAlert({ text: err.response.data?.message ?? 'Error desconegut' })
        })
    },
    [preu, tokenExpiration, router]
  )

  return (
    <div>
      <TitleMenu>Admin!</TitleMenu>
      <Card>
        <TitleCard>Configuració</TitleCard>
        <div className="flex flex-col mt-2">
          <form
            className="flex flex-col w-full space-y-6"
            action="#"
            method="POST"
            onSubmit={saveSettings}>
            <Input
              label="Preu hora (cèntims)"
              name="preu"
              type="number"
              min="0"
              error={errors?.price_hour}
              required
              placeholder="Preu hora (cèntims)"
              value={preu}
              onInput={changePreu}
            />
            <Input
              label="Temps vàlid d'un QR (minuts)"
              name="Temps"
              type="number"
              min="5"
              max="60"
              error={errors?.token_expiration}
              required
              placeholder="Temps vàlid d'un QR (minuts)"
              value={tokenExpiration}
              onInput={changeToken}
            />
            <div className="flex flex-row items-center justify-between">
              <Link href="/">
                <div className="flex items-center space-x-2 text-sm tracking-wider text-gray-600">
                  <ArrowLeft />
                  <a>Enrere</a>
                </div>
              </Link>
              <Button>Guardar</Button>
            </div>
          </form>
        </div>
      </Card>

      <Card>
        <TitleCard>Logs</TitleCard>
        <div className="w-full mt-2 overflow-x-auto mt-">
          <div className="border-b border-gray-200 shadow">
            <table className="w-full">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-3 py-3 text-sm text-center">Matrícula</th>
                  <th className="px-3 py-3 text-sm text-center">Estat</th>
                  <th className="px-3 py-3 text-sm text-center">Preu</th>
                  <th className="px-3 py-3 text-sm text-center">Pagament</th>
                  <th className="px-3 py-3 text-sm text-center">Data</th>
                </tr>
              </thead>
              <tbody className="bg-white">
                {logs?.data?.map((log) => (
                  <tr key={log.id} className="whitespace-nowrap">
                    <td className="px-3 py-3 text-sm text-center">{log.car?.matricula}</td>
                    <td className="px-3 py-3 text-sm text-center">{log.state}</td>
                    <td className="px-3 py-3 text-sm text-center">
                      {log.state != 'Pagament' ? (
                        <div></div>
                      ) : (
                        <>
                          {(log.price / 100).toLocaleString('es', {
                            useGrouping: false,
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                          })}{' '}
                          €
                        </>
                      )}
                    </td>
                    <td className="px-3 py-3 text-sm text-center">
                      {log.payment_id == null ? (
                        <div></div>
                      ) : (
                        <a
                          className="underline"
                          href={`https://dashboard.stripe.com/test/payments/${log.payment_id}`}
                          target="_blank">
                          Veure
                        </a>
                      )}
                    </td>
                    <td className="px-3 py-3 text-sm text-center">
                      {dayjs(log.created_at).format('DD/MM/YYYY HH:mm')}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </Card>
    </div>
  )
}

export default admin(Page)
