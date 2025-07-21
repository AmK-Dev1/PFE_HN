import React from 'react';
import "./styles/NavBar.css";

function NavBar() {
    return (
        <nav className="navbar navbar-expand-lg m-0 sticky-top">
            <div className="container-fluid">
                <h1 className="navbar-brand" style={{color: "#ffffff" }}>Gestion des Factures</h1>

                <button
                    className="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span className="navbar-toggler-icon"></span>
                </button>

                <div className="collapse navbar-collapse" id="navbarNav">
                    <ul className="navbar-nav me-auto">
                        <li className="nav-item">
                            <a className="nav-link" href="/gf/clients">Clients</a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="/gf/articles">Articles</a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="/gf/soumissions">Soumissions</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    );
}

export default NavBar;
