import axios from 'axios'
import Link from 'next/link'
import { useSWRConfig } from 'swr'
import { useRouter } from 'next/router'
import React, { useState, useCallback } from 'react'
import Card from '../../components/card'
import Input from '../../components/input'
import Button from '../../components/button'
import TitleMenu from '../../components/titleMenu'
import ArrowLeft from '../../components/icons/arrowLeft'
import { auth } from '../../context/user'
import { successAlert, errorAlert } from '../../lib/alerts'

function editCar() {
  const router = useRouter()
  const { mutate } = useSWRConfig()
  const [matricula, setMatricula] = useState('')
  const [errors, setErrors] = useState(undefined)

  const changeMatricula = useCallback(
    (event) => setMatricula(event.currentTarget.value),
    [setMatricula]
  )

  const { id } = router.query

  const editCar = useCallback(
    (event) => {
      event.preventDefault()
      setErrors(undefined)

      axios
        .put(`/cars/${id}`, { matricula })
        .catch(function (err) {
          setErrors(err.response?.data?.message)
          errorAlert({ text: err.response.data?.message ?? 'Error desconegut' })
        })
        .then(() => mutate('/cars'))
        .then(() => successAlert({ text: "S'han actualitzat les configuracions del cotxe" }))
        .then(() => router.push('/'))
    },
    [matricula, router]
  )

  return (
    <div>
      <TitleMenu>Editar Vehicle!</TitleMenu>
      <Card>
        <div className="flex flex-col">
          <form
            className="w-full flex flex-col space-y-6"
            action="#"
            method="PUT"
            onSubmit={editCar}>
            <Input
              label="Matrícula"
              name="matrícula"
              error={errors}
              required
              placeholder="Matrícula"
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

export default auth(editCar)
