import React from 'react'

const explication = ({ children }) => {
  return (
    <div>
      <div className="flex flex-col md:items-center m-6 md:mx-auto text-justify">
        <div className=" md:w-1/2">
          <div className="flex flex-col tracking-wider text-lg justify-start">{children}</div>
        </div>
      </div>
    </div>
  )
}

export default explication
