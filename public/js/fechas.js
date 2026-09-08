import React from "https://esm.sh/react@18.2.0"
import ReactDOM from "https://esm.sh/react-dom@18.2.0/client"

const appDonElement = document.getElementById('fechas');
const root = ReactDOM.createRoot(appDonElement);


const c = React.createElement;


const fechas = c("div", {
    class: "form-inline"
}, c("label", {
    class: "my-1 mr-2",
    for: "txtfechai"
}, "Inicio"), c("input", {
    type: "date",
    class: "form-control form-control-sm",
    id: "txtfechai",
    name: "txtfechai"
}), "\xa0", c("label", {
    class: "my-1 mr-2",
    for: "txtfechai"
}, "Hasta"), c("input", {
    type: "date",
    class: "form-control form-control-sm",
    id: "txtfechaf",
    name: "txtfechaf"
}), c("button", {
    class: "btn btn-primary my-1",
    id: 'btnconsultar'
}, "Consultar"));

// const buttonLike =c('button',null,'Me gusta')

const app = c(React.Fragment, null, [fechas])

root.render(app)