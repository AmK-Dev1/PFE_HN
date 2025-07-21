
import {Routes, Route} from "react-router-dom";
import React from 'react';


import GfDashboard from "../gf/GfDashboard.jsx";
import GfClients from "../gf/GfClients.jsx";
import GfArticles from "../gf/GfArticles.jsx";
import GfArticlesMatériel from "../gf/GfArticlesMatériel.jsx";

import "./index.css"

const Index = () => {


    return (
        <Routes>
            <Route path="/gf" element={<GfDashboard/>}/>
            <Route path="/gf/clients" element={<GfClients/>}/>
            <Route path="/gf/articles" element={<GfArticles/>} />
            <Route path="/gf/articles/matériel" element={ < GfArticlesMatériel/> }/>
        </Routes>
    );
}
export default Index;
