import React, { useEffect, useState } from 'react';
import axios from 'axios';
import styles from './styles/GfArticles.module.css';

const GfArticles = () => {

    const [articles, setArticles] = useState([]);
    const [filteredArticles, setFilteredArticles] = useState([]);
    const [formData, setFormData] = useState({
        name: '',
        description: '',
        unit_cost: 100,
        type: 'maindoeuvre',
        reference: '',
        unit_of_measure: ''
    });
    const [filterType, setFilterType] = useState('');
    const [editId, setEditId] = useState(null);
    const [isFormVisible, setIsFormVisible] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');
    const [FFE, setFFE] = useState(0.86);

    useEffect(() => {
        fetchArticles();
    }, []);

    const fetchArticles = async () => {
        try {
            const response = await axios.get('/api/article');
            setArticles(response.data.data);
            setFilteredArticles(response.data.data);
        } catch (error) {
            console.error('Error loading articles:', error);
        }
    };

    const calculateCostOfProduct = (unitCost) => {
        return unitCost * (1 + FFE);
    };

    const handleFilterChange = (e) => {
        const selectedType = e.target.value;
        setFilterType(selectedType);

        if (selectedType === '') {
            setFilteredArticles(articles);
        } else {
            const filtered = articles.filter(article => article.type === selectedType);
            setFilteredArticles(filtered);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    const handleAddArticle = async (e) => {
        e.preventDefault();
        setErrorMessage('');
        try {
            const response = await axios.post('/api/article', formData);
            setArticles([...articles, response.data.data]);
            setFilteredArticles([...articles, response.data.data]);
            closeForm();
        } catch (error) {
            setErrorMessage('Échec de l\'ajout de l\'article. Veuillez vérifier vos informations.');
        }
    };

    const handleDeleteArticle = async (id) => {
        try {
            await axios.delete(`/api/article/${id}`);
            setArticles(articles.filter(article => article.id !== id));
            setFilteredArticles(articles.filter(article => article.id !== id));
        } catch (error) {
            console.error('Erreur lors de la suppression de l\'article:', error);
        }
    };

    const handleEditArticle = (article) => {
        setEditId(article.id);
        setFormData({
            name: article.name,
            description: article.description,
            unit_cost: article.unit_cost,
            type: article.type,
            reference: article.reference,
            unit_of_measure: article.unit_of_measure
        });
        setIsFormVisible(true);
    };

    const handleUpdateArticle = async (e) => {
        e.preventDefault();
        setErrorMessage('');
        try {
            const response = await axios.put(`/api/article/${editId}`, formData);
            setArticles(articles.map(article => (article.id === editId ? response.data.data : article)));
            setFilteredArticles(articles.map(article => (article.id === editId ? response.data.data : article)));
            closeForm();
        } catch (error) {
            setErrorMessage('Échec de la mise à jour de l\'article. Veuillez vérifier vos informations.');
        }
    };

    const openForm = () => {
        setIsFormVisible(true);
        clearForm();
        setEditId(null);
    };

    const closeForm = () => {
        setIsFormVisible(false);
        setErrorMessage('');
    };

    const clearForm = () => {
        setFormData({
            name: '',
            description: '',
            unit_cost: 100,
            type: 'maindoeuvre',
            reference: '',
            unit_of_measure: ''
        });
    };

    return (
        <div className={styles.container}>
            <h2>Gestion des Articles</h2>


            <div className={styles.buttonFilterContainer}>
                <div className={styles.leftContainer}>
                    <button onClick={openForm} className={`${styles.button} ${styles.add}`}>
                        Ajouter un Article
                    </button>

                    {/* Afficher le taux FFE ici */}
                    <div className={styles.ffeContainer}>
                        <label>Facteur FE : </label>
                        <span
                            className={styles.ffeValue}>{(FFE * 100).toFixed(2)}%</span> {/* Présenter le FFE en pourcentage */}
                    </div>
                </div>

                <div className={styles.filterWrapper}>
                    <label htmlFor="filterType">Filtrer par Type :</label>
                    <select
                        id="filterType"
                        value={filterType}
                        onChange={handleFilterChange}
                        className={styles.filter}
                    >
                        <option value="">Tous les Types</option>
                        <option value="maindoeuvre">Main-d'œuvre</option>
                        <option value="transport">Transport</option>
                        <option value="soutraitance">Sous-traitance</option>
                        <option value="material">Matériel</option>
                    </select>
                </div>
            </div>


            <table className={styles.table}>
                <thead>
                <tr>
                    <th>Référence</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Coût Unitaire</th>
                    <th>Coût Avec FFE</th>
                    <th>Type</th>
                    <th>Unité de Mesure</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                {filteredArticles.map(article => (
                    <tr key={article.id}>
                        <td>{article.reference}</td>
                        <td>{article.name}</td>
                        <td>{article.description}</td>
                        <td>{article.unit_cost}</td>
                        <td>{calculateCostOfProduct(article.unit_cost).toFixed(2)}</td>
                        <td>{article.type}</td>
                        <td>{article.unit_of_measure || 'N/A'}</td>
                        <td>
                            <button onClick={() => handleEditArticle(article)}
                                    className={`${styles.button} ${styles.edit}`}>Modifier
                            </button>
                            <button onClick={() => handleDeleteArticle(article.id)}
                                    className={`${styles.button} ${styles.delete}`}>Supprimer
                            </button>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            {/* Overlay Form */}
            {isFormVisible && (
                <div className={styles.modalOverlay}>
                    <div className={styles.modal}>
                        <h3>{editId ? 'Modifier un Article' : 'Ajouter un Nouvel Article'}</h3>
                        {errorMessage && <p className={styles.errorMessage}>{errorMessage}</p>}
                        <form onSubmit={editId ? handleUpdateArticle : handleAddArticle} className={styles.form}>
                            <input type="text" name="reference" value={formData.reference} onChange={handleChange}
                                   placeholder="Référence" required/>
                            <input type="text" name="name" value={formData.name} onChange={handleChange}
                                   placeholder="Nom de l'Article" required/>
                            <textarea name="description" value={formData.description} onChange={handleChange}
                                      placeholder="Description"/>
                            <input type="number" name="unit_cost" value={formData.unit_cost} onChange={handleChange}
                                   placeholder="Coût Unitaire" required/>

                            {/* Sélecteur pour le type d'article */}
                            <label htmlFor="type">Type :</label>
                            <select id="type" name="type" value={formData.type} onChange={handleChange}
                                    className={styles.select}>
                                <option value="maindoeuvre">Main-d'œuvre</option>
                                <option value="transport">Transport</option>
                                <option value="soutraitance">Sous-traitance</option>
                                <option value="material">Matériel</option>
                            </select>

                            <input
                                type="text"
                                name="unit_of_measure"
                                value={formData.unit_of_measure}
                                onChange={handleChange}
                                placeholder="Unité de Mesure (si applicable)"
                            />

                            <div className={styles.buttonContainer}>
                                <button type="submit"
                                        className={styles.button}>{editId ? 'Modifier' : 'Ajouter'} l'Article
                                </button>
                                <button type="button" onClick={clearForm} className={styles.button}>Réinitialiser
                                </button>
                                <button type="button" onClick={closeForm}
                                        className={`${styles.button} ${styles.cancel}`}>Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default GfArticles;
