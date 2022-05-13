import React from 'react'

const titleMenu = ({ children }) => {
  return (
    <div>
      <div className="flex md:w-1/2 md:items-start md:mx-auto md:my-8 m-6">
        <div className="font-extrabold text-3xl tracking-wider text-pink-700">{children}</div>
      </div>
    </div>
  )
}

export default titleMenu
