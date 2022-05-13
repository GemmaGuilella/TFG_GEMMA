import axios from 'axios'
import React, { useState, useCallback } from 'react'
import Card from '../../components/card'
import Link from 'next/link'
import TitleMenu from '../../components/titleMenu'
import ArrowLeft from '../../components/icons/arrowLeft'
import { useRouter } from 'next/router'
import { auth } from '../../context/user'
import { useUser } from '../../context/user'
import Input from '../../components/input'
import Button from '../../components/button'
import { successAlert, errorAlert } from '../../lib/alerts'

function edit() {
  const router = useRouter()
  const [dni, setDni] = useState('')
  const [name, setName] = useState('')
  const [phone, setPhone] = useState('')
  const [password, setPassword] = useState('')
  const [password_confirmation, setpassword_confirmation] = useState('')

  const changeDni = useCallback((event) => setDni(event.currentTarget.value), [setDni])
  const changePhone = useCallback((event) => setPhone(event.currentTarget.value), [setPhone])
  const changeName = useCallback((event) => setName(event.currentTarget.value), [setName])
  const changePassword = useCallback(
    (event) => setPassword(event.currentTarget.value),
    [setPassword]
  )
  const changepassword_confirmation = useCallback(
    (event) => setpassword_confirmation(event.currentTarget.value),
    [setpassword_confirmation]
  )

  const [error, setError] = useState('')
  const { user, setUser } = useUser()

  const editUser = useCallback(
    (event) => {
      event.preventDefault()
      setError(undefined)

      axios
        .put('/auth/user', {
          name,
          dni,
          phone,
          password,
          password_confirmation,
        })
        .then((resposta) => setUser({ ...user, user: resposta.data.data }))
        .catch((e) => setError(e.resposta.data?.message))
        .then(() => successAlert({ text: "S'han actualitzat les dades de l'usuari" }))
        .then(() => router.push('/'))
    },
    [name, dni, phone, password, password_confirmation, user, router]
  )

  return (
    <div>
      <TitleMenu>Editar Usuari!</TitleMenu>
      <Card>
        <div className="flex flex-col">
          <form
            className="w-full flex flex-col space-y-6"
            action="#"
            method="PUT"
            onSubmit={editUser}>
            <Input
              label="Nom"
              name="Nom"
              error={error}
              required
              placeholder="Nom"
              value={name}
              onInput={changeName}
            />
            <Input
              label="Contrasenya"
              name="Password"
              type="Password"
              error={error}
              required
              placeholder="Contrasenya"
              value={password}
              onInput={changePassword}
            />
            <Input
              label="Confirmació de Contrasenya"
              name="Password"
              type="Password"
              error={error}
              required
              placeholder="Confirmació de la contrasenya"
              value={password_confirmation}
              onInput={changepassword_confirmation}
            />
            <Input
              label="Telèfon"
              name="Telèfon"
              error={error}
              required
              placeholder="Telèfon"
              value={phone}
              onInput={changePhone}
            />
            <Input
              label="DNI"
              name="DNI"
              error={error}
              required
              placeholder="DNI"
              value={dni}
              onInput={changeDni}
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

export default auth(edit)
