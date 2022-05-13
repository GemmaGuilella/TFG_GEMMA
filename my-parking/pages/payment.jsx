import React from 'react'
import TitleMenu from '../components/titleMenu'
import Card from '../components/card'
import Explication from '../components/explication'
function payment() {
  return (
    <>
      <TitleMenu>Pagament!</TitleMenu>
      <Explication>
        <span>1. Fes el pagament.</span>
        <span>2. Es generarà un codi QR per sortir del pàrquing (vàlid durant 5 minuts)</span>
        <span>3. Que tinguis un bon viatge! Fins a la pròxima!</span>
      </Explication>
      <Card>
        <div className="flex flex-row mx-6 space-x-10 m-4 justify-between">No implementat!</div>
      </Card>
    </>
  )
}

export default payment
