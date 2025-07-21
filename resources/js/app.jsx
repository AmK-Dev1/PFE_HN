import './bootstrap';

import React from 'react';
import ReactDOM from 'react-dom/client';
import App from "./ReactFrontend/App";
import {BrowserRouter} from "react-router-dom";


const rootElement = document.getElementById('app');


if (rootElement) {
    const root = ReactDOM.createRoot(rootElement);
    root.render(
        <>
            <App />
        </>
    );
}





