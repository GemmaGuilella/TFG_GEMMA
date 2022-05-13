import '../styles/globals.css'
import axios from 'axios'
import { UserContext, hasUser } from '../context/user'
import { useCallback, useEffect, useState } from 'react'
import { SWRConfig, useSWRConfig } from 'swr'
import Layout from '../components/layout'
import Pusher from 'pusher-js'
import { useRouter } from 'next/router'

// axios.defaults.baseURL = 'http://127.0.0.1:8000/api'
axios.defaults.baseURL = 'http://10.10.10.1:8000/api'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

const swrConfig = {
  async fetcher(url) {
    const response = await axios.get(url)
    return response.data
  },
}

function MyApp({ Component, pageProps }) {
  const [user, setRawUser] = useState(undefined)
  const [isLoading, setIsLoading] = useState(true)
  const router = useRouter()
  const { mutate } = useSWRConfig()

  const setUser = useCallback(
    (data) => {
      localStorage.setItem('user', JSON.stringify(data))
      if (hasUser(data)) axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
      else delete axios.defaults.headers.common['Authorization']
      setRawUser(data)
    },
    [setRawUser]
  )

  useEffect(() => {
    const storageUser = JSON.parse(localStorage.getItem('user'))
    if (storageUser === null || storageUser.token === undefined || storageUser.token === null) {
      setIsLoading(false)
      return
    }

    axios
      .get('/auth/user', { headers: { Authorization: `Bearer ${storageUser.token}` } })
      .then((res) => {
        setUser({ token: storageUser.token, user: res.data.data })
        setIsLoading(false)
      })
      .catch((e) => {
        console.log(e)
        setIsLoading(false)
      })
  }, [setUser, setIsLoading])

  useEffect(() => {
    const pusher = new Pusher(process.env.NEXT_PUBLIC_REACT_APP_PUSHER_KEY, {
      cluster: 'eu',
    })
    var channel = pusher.subscribe('my-channel')
    channel.bind('my-event', async function (data) {
      // console.log(data)

      // console.log(data.car_id)
      // console.log(data.user_id)

      if (data.user_id === user.user.id) {
        await Promise.all([mutate(`/cars/${data.car_id}`), mutate('/cars')])
        await router.push(`/history/${data.car_id}`)
      }
    })
    return () => {
      pusher.unsubscribe('my-channel')
    }
  }, [user, router, mutate])

  return (
    // Fem que tots els components tinguin l'usuari
    <UserContext.Provider value={{ user, setUser, isLoading }}>
      <SWRConfig value={swrConfig}>
        <Layout>
          <Component {...pageProps} />
        </Layout>
      </SWRConfig>
    </UserContext.Provider>
  )
}

export default MyApp
