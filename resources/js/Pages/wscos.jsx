import React , {useState} from 'react'

const Wscos = () => {
    const [h,seth] = useState(0);
    let d = ()=>{
        h<20? setTimeout(()=>{
            seth(h+1);
        },100) : seth(0);
    }
    d();
  return (
    <div>
        <center>
        <h1>{h}</h1>
        </center>
    </div>
  )
}

export default Wscos;