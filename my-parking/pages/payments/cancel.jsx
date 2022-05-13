import React from 'react'
import Card from '../../components/card'
import No from '../../components/icons/x'
import Button from '../../components/button'
import { auth } from '../../context/user'
import Link from 'next/link'
import classnames from 'classnames'

function cancel() {
  return (
    <div>
      <Card>
        <div className="flex flex-col items-center justify-center space-y-6">
          <No className={classnames('h-32 w-32 text-red-500')} />
          <p className="text-center">No ha anat bé el pagament</p>
          <Button>
            <Link href="/">Inici</Link>
          </Button>
        </div>
      </Card>
    </div>
  )
}

export default auth(cancel)
