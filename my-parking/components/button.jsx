import React from 'react'
import classnames from 'classnames'

const Button = ({ children, type = 'submit', className, ...props }) => {
  return (
    <button
      type={type}
      className={classnames(
        'flex py-1.5 px-3 bg-gray-700 rounded-md text-white tracking-wider shadow',
        className
      )}
      {...props}>
      {children}
    </button>
  )
}

export default Button
