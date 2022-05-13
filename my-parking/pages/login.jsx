import axios from 'axios'
import Link from 'next/link'
import { useUser, guest } from '../context/user'
import React, { useState, useMemo, useCallback } from 'react'
import Input from '../components/input'
import Button from '../components/button'
import Configuration from '../components/icons/configuration'
import Statistics from '../components/icons/statistics'

function FormLogin() {
  // const [email, setEmail] = useState('gemma@guilella.com')
  // const [password, setPassword] = useState('Password123!')
  const [email, setEmail] = useState('admin@admin.com')
  const [password, setPassword] = useState('Password123!')
  const device_name = useMemo(() => 'web', [])

  const changeEmail = useCallback((event) => setEmail(event.currentTarget.value), [setEmail])
  const changePassword = useCallback(
    (event) => setPassword(event.currentTarget.value),
    [setPassword]
  )

  const [error, setError] = useState('')
  const { user, setUser } = useUser()

  const login = useCallback(
    (event) => {
      event.preventDefault()
      setError(undefined)

      axios
        .post('/auth/login', { email, password, device_name })
        .then((resposta) => setUser(resposta.data))
        .catch(setError)
    },
    [email, password, device_name]
  )

  return (
    <>
      <div className="mx-6 my-16 md:p-24 md:m-0">
        <div className="max-h-fit flex flex-wrap border border-gray-200 shadow-md">
          <div className="w-0 md:w-1/2 bg-gray-50 flex py-6 md:p-6 items-center md:border-pink-600 md:border-r-2 md:border-b-0 border-b border-pink-600">
            <div className="flex flex-col w-full space-y-6">
              <div className="flex flex-row items-center space-x-6 md:mx-12">
                <div className="bg-pink-200 rounded-full">
                  <Statistics />
                </div>
                <div className="text-left text-sm tracking-widest uppercase">Estadístiques</div>
              </div>
              <div className="flex mx-12 border border-gray-700 rounded-md"></div>
              <div className="flex flex-row items-center space-x-6 md:mx-12">
                <div className="bg-pink-200 rounded-full">
                  <Configuration />
                </div>
                <div className="text-left text-sm tracking-widest uppercase">Configuració</div>
              </div>
              <div className="flex mx-12 border border-gray-700 rounded-md"></div>
              <div className="flex flex-row items-center space-x-6 md:mx-12">
                <div className="h-12 sm:w-12 bg-pink-200 rounded-full">
                  <Configuration />
                </div>
                <div className="text-left text-sm tracking-widest uppercase">Control</div>
              </div>
            </div>
          </div>
          <div className="w-full md:w-1/2 flex items-center justify-center bg-white">
            <div className="flex flex-col m-6 w-full space-y-6">
              <div className="flex flex-row tracking-wider items-center justify-center">
                <h2 className="text-3xl">App</h2>
                <h2 className="text-4xl font-extrabold text-pink-600">Arkem</h2>
              </div>
              {/* <div className="">{user?.token ?? 'No sha iniciat sessio'}</div> */}
              <div className="flex flex-col">
                <form
                  className="w-full flex flex-col space-y-6"
                  action="#"
                  method="POST"
                  onSubmit={login}>
                  <Input
                    label="Email"
                    name="Email"
                    error={error}
                    required
                    placeholder="Email"
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
                  <div className="flex flex-col justify-center items-center space-y-3">
                    <Button>Iniciar Sessió</Button>
                    <button>
                      <Link href="/signin" passHref>
                        <p className="text-gray-600">Crear un nou compte</p>
                      </Link>
                    </button>
                  </div>
                </form>
              </div>
              {error && <div>Error: {error.message}</div>}
            </div>
          </div>
        </div>
      </div>
    </>
  )
}

export default guest(FormLogin)
