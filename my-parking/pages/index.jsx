import useSWR from 'swr'
import axios from 'axios'
import Link from 'next/link'
import Card from '../components/card'
import React, { useCallback } from 'react'
import Edit from '../components/icons/edit'
import Plus from '../components/icons/plus'
import Email from '../components/icons/email'
import Phone from '../components/icons/phone'
import TitleCard from '../components/titleCard'
import { auth, useUser } from '../context/user'
import Admin from '../components/icons/admin'
import Identification from '../components/icons/identification'
import User from '../components/icons/user'
import QRCode from '../components/icons/qrcode'
import Location from '../components/icons/location'
import Trash from '../components/icons/trash'
import Button from '../components/button'
import { successAlert, errorAlert } from '../lib/alerts'
function index() {
  const { user } = useUser()
  const { data: cars, mutate } = useSWR('/cars')

  const deleteCar = useCallback((id) => {
    axios
      .delete(`/cars/${id}`, {})
      .then(() => mutate())
      .then(() => successAlert({ text: "S'ha eliminat el cotxe" }))
      .catch(() => errorAlert())
  }, [])

  return (
    <div>
      <div className="flex flex-row md:mx-auto md:w-1/2 justify-end container px-6 my-6 md:px-0 space-x-3">
        <div className="flex flex-row md:mx-auto container">
          <div className="font-extrabold text-2xl tracking-wider">
            Benvingut/da <span className="font-bold text-pink-700">{user.user.name}!</span>
          </div>
        </div>
        <div className="flex text-center items-center">
          <Link href="/admin/admin">
            <button
              disabled={!user.user.admin}
              className="text-sm flex rounded-md p-1.5 bg-gray-700 text-white tracking-wider shadow disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-300">
              <Admin />
              Admin
            </button>
          </Link>
        </div>
      </div>
      <Card>
        <div className="flex flex-row justify-between mb-6">
          <TitleCard>Perfil</TitleCard>
          <Link href="/user/edit">
            <a>
              <Edit />
            </a>
          </Link>
        </div>
        <div className="flex flex-col space-y-3">
          <div className="flex flex-row space-x-6">
            <Email />
            <div>{user.user.email}</div>
          </div>
          <div className="flex flex-row space-x-6">
            <User />
            <div>{user.user.name}</div>
          </div>
          <div className="flex flex-row space-x-6">
            <Identification />
            <div>{user.user.dni}</div>
          </div>
          <div className="flex flex-row space-x-6">
            <Phone />
            <div>{user.user.phone}</div>
          </div>
        </div>
      </Card>
      <Card>
        <div className="flex flex-row justify-between mb-6">
          <TitleCard>Vehicles</TitleCard>
          <Link href="/cars/add">
            <a className="flex flex-row p-1.5 bg-gray-700 rounded-md text-white tracking-wider shadow">
              <Plus />
            </a>
          </Link>
        </div>
        {cars?.data.map((car) => (
          <div key={car.id}>
            <div className="flex flex-row items-center justify-between">
              <a>{car.matricula}</a>
              <div className="flex space-x-3">
                {!car.is_parked ? (
                  <>
                    <Link href={`/QR/${car.id}`}>
                      <div className="mt-1.5 text-gray-700">
                        <QRCode />
                      </div>
                    </Link>
                    <div className="mt-1.5 text-gray-700">
                      <Link href={`/cars/${car.id}`}>
                        <a>
                          <Edit />
                        </a>
                      </Link>
                    </div>
                  </>
                ) : (
                  <>
                    <Link href={`/history/${car.id}`}>
                      <div className="mt-1.5 text-gray-700">
                        <Location />
                      </div>
                    </Link>
                    <div className="mt-1.5 text-gray-200">
                      <Edit />
                    </div>
                  </>
                )}
                <button
                  disabled={car.is_parked}
                  onClick={() => deleteCar(car.id)}
                  className="text-red-500 disabled:text-red-200">
                  <Trash />
                </button>
              </div>
            </div>
          </div>
        ))}
      </Card>
    </div>
  )
}

export default auth(index)
