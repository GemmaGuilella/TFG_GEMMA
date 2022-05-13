import React from 'react'

const titleCard = ({ children }) => {
  return (
    <div className="flex flex-col">
      <div className="text-xl font-bold items-start tracking-wider uppercase">{children}</div>
    </div>
  )
}

export default titleCard
