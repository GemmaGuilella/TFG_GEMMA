import axios from 'axios'
import Link from 'next/link'
import { useUser, guest } from '../context/user'
import React, { useState, useMemo, useCallback } from 'react'
import Card from '../components/card'
import TitleCard from '../components/titleCard'
import Button from '../components/button'
import Input from '../components/input'

function signin() {
  const [dni, setDni] = useState('')
  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [phone, setPhone] = useState('')
  const [password, setPassword] = useState('')

  const [password_confirmation, setpassword_confirmation] = useState('')

  const device_name = useMemo(() => 'web', [])
  const changeDni = useCallback((event) => setDni(event.currentTarget.value), [setDni])
  const changePhone = useCallback((event) => setPhone(event.currentTarget.value), [setPhone])
  const changeName = useCallback((event) => setName(event.currentTarget.value), [setName])
  const changeEmail = useCallback((event) => setEmail(event.currentTarget.value), [setEmail])
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

  const register = useCallback(
    (event) => {
      event.preventDefault()
      setError(undefined)

      axios
        .post('/auth/register', {
          name,
          dni,
          email,
          phone,
          password,
          password_confirmation,
          device_name,
        })
        .then((resposta) => setUser(resposta.data))
        .catch(setError)
    },
    [name, dni, email, phone, password, password_confirmation, device_name]
  )

  return (
    <>
      <div className="flex  flex-row tracking-wider items-center justify-center my-6">
        <h2 className="text-3xl">App</h2>
        <h2 className="text-4xl font-extrabold text-pink-600">Arkem</h2>
      </div>
      <Card>
        <div className="flex flex-row justify-between mb-6">
          <TitleCard>Registre d'Usuari</TitleCard>
        </div>
        <div className="flex flex-col">
          <form
            className="w-full flex flex-col space-y-6"
            action="#"
            method="POST"
            onSubmit={register}>
            <Input
              label="Nom"
              name="Nom"
              error={error}
              required
              placeholder="Nom"
              value={name}
              onInput={changeName}
            />
            <div className="flex flex-col sm:flex-row justify-between space-y-6 md:space-y-0">
              <Input
                label="Dni"
                name="Dni"
                error={error}
                required
                placeholder="Dni"
                value={dni}
                onInput={changeDni}
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
            </div>
            <Input
              label="E-mail"
              name="E-mail"
              error={error}
              required
              placeholder="E-mail"
              value={email}
              onInput={changeEmail}
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
              label="Confirmar Contrsenya"
              name="Confirmar_Contrasenya"
              type="Password"
              error={error}
              required
              placeholder="Confirmar Contrasenya"
              value={password_confirmation}
              onInput={changepassword_confirmation}
            />
            <div className="flex flex-col justify-center items-center space-y-3">
              <Button>Registrar-se</Button>
              <button>
                <Link href="/login" passHref className="text-pink-700 hover:text-pink-900">
                  <p className="text-gray-600">Ja tinc compte</p>
                </Link>
              </button>
            </div>
          </form>
        </div>
      </Card>
    </>
  )
}

export default guest(signin)
