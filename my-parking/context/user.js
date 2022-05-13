import { useContext, createContext, useEffect } from 'react'
import { useRouter } from 'next/router'

export const UserContext = createContext(undefined)
export const useUser = () => useContext(UserContext)

export const hasUser = (user) => user !== undefined && user !== null

export const auth = (Component) => (props) => {
  const { user, isLoading } = useUser()
  const router = useRouter()

  useEffect(() => {
    if (isLoading === false && !hasUser(user)) {
      router.push('/login')
    }
  }, [user, isLoading])

  if (isLoading) return null
  if (!hasUser(user)) return null

  return <Component {...props} />
}

export const guest = (Component) => (props) => {
  const { user, isLoading } = useUser()
  const router = useRouter()

  useEffect(() => {
    if (isLoading === false && hasUser(user)) {
      router.push('/')
    }
  }, [user, isLoading])

  return isLoading === false && !hasUser(user) && <Component {...props} />
}

export const admin = (Component) => (props) => {
  const { user, isLoading } = useUser()
  const router = useRouter()

  useEffect(() => {
    if (isLoading === false && !hasUser(user)) {
      router.push('/login')
    }
    if (isLoading === true && hasUser(user) && !user.user.admin) {
      router.push('/')
    }
  }, [user, isLoading])

  if (isLoading) return null
  if (!hasUser(user)) return null
  if (!user.user.admin) return null

  return <Component {...props} />
}
