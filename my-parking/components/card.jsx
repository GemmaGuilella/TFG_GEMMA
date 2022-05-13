import React from 'react'

const card = ({ children }) => {
  return (
    <>
      <div className="flex flex-col md:items-center md:mx-auto m-6">
        <div className="p-6 md:w-1/2 bg-white border border-gray-100 shadow-md rounded-md">
          {children}
        </div>
      </div>
    </>
  )
}

export default card
