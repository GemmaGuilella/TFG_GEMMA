import React from 'react'

const title = ({ children }) => {
  return (
    <div>
      <div className="mt-2 text-xl">
        <span className="tracking-wider">{children}</span>
      </div>
    </div>
  )
}

export default title
