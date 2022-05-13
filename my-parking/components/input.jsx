import classnames from 'classnames'

const Input = ({ error, label, ...props }) => {
  return (
    <div className="flex flex-col space-y-1">
      <label className="flex flex-col w-full space-y-1">
        <span>{label}</span>
        <input
          className={classnames(
            'appearance-none rounded relative block w-full px-3 py-2 border-2 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-pink-700 focus:border-pink-700 focus:z-10 sm:text-sm',
            { 'border-gray-200': !error, 'border-red-500': error }
          )}
          {...props}
        />
      </label>
      {error && <span className="text-red-500">{error}</span>}
    </div>
  )
}

export default Input
