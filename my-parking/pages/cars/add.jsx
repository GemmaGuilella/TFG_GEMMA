import axios from 'axios'
import React, { useState, useCallback } from 'react'
import Card from '../../components/card'
import Link from 'next/link'
import TitleMenu from '../../components/titleMenu'
import ArrowLeft from '../../components/icons/arrowLeft'
import { useSWRConfig } from 'swr'
import { useRouter } from 'next/router'
import { auth } from '../../context/user'
import Input from '../../components/input'
import Button from '../../components/button'
import { successAlert, errorAlert } from '../../lib/alerts'

function addCars() {
  const router = useRouter()
  const { mutate } = useSWRConfig()
  const [matricula, setMatricula] = useState('')
  const [errors, setErrors] = useState(undefined)

  const changeMatricula = useCallback(
    (event) => setMatricula(event.currentTarget.value),
    [setMatricula]
  )

  const saveCar = useCallback(
    (event) => {
      event.preventDefault()
      setErrors(undefined)

      axios
        .post('/cars', { matricula })
        .then(async function (response) {
          await mutate('/cars')
          successAlert({ text: "S'han afegit els cotxes" })
          await router.push('/')
        })
        .catch(function (err) {
          setErrors(err.response.data?.message)
          errorAlert({ text: err.response.data?.message ?? 'Error desconegut' })
        })
    },
    [matricula, router]
  )

  return (
    <div>
      <TitleMenu>Afegir Vehicles!</TitleMenu>
      <Card>
        <div className="flex flex-col">
          <form
            className="w-full flex flex-col space-y-6"
            action="#"
            method="POST"
            onSubmit={saveCar}>
            <Input
              label="Número Matricula"
              name="matricula"
              error={errors}
              required
              placeholder="Matricula Per ex. XXXAAA"
              value={matricula}
              onInput={changeMatricula}
            />
            <div className="flex flex-row justify-between items-center">
              <Link href="/">
                <div className="flex text-sm items-center space-x-2 text-gray-600 tracking-wider">
                  <ArrowLeft />
                  <a>Enrere</a>
                </div>
              </Link>
              <Button>Guardar</Button>
            </div>
          </form>
        </div>
      </Card>
    </div>
  )
}

export default auth(addCars)
