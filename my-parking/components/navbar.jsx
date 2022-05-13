import axios from 'axios'
import Link from 'next/link'
import { useUser } from '../context/user'
import { useRouter } from 'next/router'
import Input from '../components/input'
import Clock from '../components/icons/clock'
import Logout from '../components/icons/logout'
import Card from '../components/card'
import QRcode from '../components/icons/qrcode'
import Location from '../components/icons/location'
import React, { useState, useMemo, useCallback } from 'react'

function navbar() {
  const { user, setUser } = useUser()
  const router = useRouter()
  const device_name = useMemo(() => 'web', [])
  const [error, setError] = useState('')

  const logout = useCallback(
    (event) => {
      event.preventDefault()
      setError(undefined)

      axios
        .post('/auth/logout', { device_name })
        .then(function (response) {
          router.reload('/login')
        })
        .catch(function (error) {
          setError(error.response.data?.message ?? 'Error desconegut')
        })
    },
    [device_name]
  )
  return (
    <>
      <div className="h-20 bg-gray-200 shadow-md">
        <div className="flex flex-row justify-between mx-6 md:mx-10">
          <div className="my-8">
            <button>
              <Link href="/" passHref className="">
                <div className="flex flex-row items-center justify-center tracking-wider">
                  <h2 className="text-xl">App</h2>
                  <h2 className="font-extrabold text-pink-600 text-md">Arkem</h2>
                </div>
              </Link>
            </button>
          </div>

          {user && (
            <div className="flex flex-row justify-between my-8 space-x-4 md:space-x-10">
              <div>
                <form action="#" method="POST" onSubmit={logout}>
                  <button type="submit">
                    <Logout />
                  </button>
                </form>
              </div>
            </div>
          )}
        </div>
      </div>
    </>
  )
}

export default navbar
