import React from 'react';
import { BrowserRouter } from "react-router-dom";
import Routes from "./router/Index";
import  NavBar from "./gf/NavBar.jsx"
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';


const App = () => {
    return (
        <div className="container-fluid p-0">
            <NavBar />
            <BrowserRouter>
                <Routes />
            </BrowserRouter>
        </div>
    );
}

export default App;
