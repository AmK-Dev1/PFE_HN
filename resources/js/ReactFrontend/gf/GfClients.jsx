import React, { useEffect, useState } from 'react';
import axios from 'axios';
import styles from './styles/GfClients.module.css';

const GfClients = () => {
    const [clients, setClients] = useState([]);
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        company_name: '',
        address: '',
        city: '',
        postal_code: '',
        country: '',
        notes: ''
    });
    const [editId, setEditId] = useState(null);
    const [isFormVisible, setIsFormVisible] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');

    useEffect(() => {
        fetchClients();
    }, []);

    const fetchClients = async () => {
        try {
            const response = await axios.get('/api/client');
            setClients(response.data.data);
        } catch (error) {
            console.error('Error loading clients:', error);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    const handleAddClient = async (e) => {
        e.preventDefault();
        setErrorMessage('');
        try {
            const response = await axios.post('/api/client', formData);
            setClients([...clients, response.data.data]);
            closeForm();
        } catch (error) {
            setErrorMessage('Failed to add client. Please check your input.');
        }
    };

    const handleDeleteClient = async (id) => {
        try {
            await axios.delete(`/api/client/${id}`);
            setClients(clients.filter(client => client.id !== id));
        } catch (error) {
            console.error('Error deleting client:', error);
        }
    };

    const handleEditClient = (client) => {
        setEditId(client.id);
        setFormData({
            name: client.name,
            email: client.email,
            phone: client.phone,
            company_name: client.company_name,
            address: client.address,
            city: client.city,
            postal_code: client.postal_code,
            country: client.country,
            notes: client.notes
        });
        setIsFormVisible(true);
    };

    const handleUpdateClient = async (e) => {
        e.preventDefault();
        setErrorMessage('');
        try {
            const response = await axios.put(`/api/client/${editId}`, formData);
            setClients(clients.map(client => (client.id === editId ? response.data.data : client)));
            closeForm();
        } catch (error) {
            setErrorMessage('Failed to update client. Please check your input.');
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
            email: '',
            phone: '',
            company_name: '',
            address: '',
            city: '',
            postal_code: '',
            country: '',
            notes: ''
        });
    };

    return (
        <div className={styles.container}>
            <h2>Gestion des Clients</h2>

            {/* Add Client Button */}
            <button onClick={openForm} className={`${styles.button} ${styles.add}`}>
                Add Client
            </button>

            {/* Table of Clients */}
            <table className={styles.table}>
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Nom de la société</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Code postal</th>
                    <th>Pays</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                {clients.map(client => (
                    <tr key={client.id}>
                        <td>{client.name}</td>
                        <td>{client.email}</td>
                        <td>{client.phone}</td>
                        <td>{client.company_name}</td>
                        <td>{client.address}</td>
                        <td>{client.city}</td>
                        <td>{client.postal_code}</td>
                        <td>{client.country}</td>
                        <td>
                            <button onClick={() => handleEditClient(client)} className={`${styles.button} ${styles.edit}`}>Edit</button>
                            <button onClick={() => handleDeleteClient(client.id)} className={`${styles.button} ${styles.delete}`}>Delete</button>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>

            {/* Overlay Form */}
            {isFormVisible && (
                <div className={styles.modalOverlay}>
                    <div className={styles.modal}>
                        <h3>{editId ? 'Edit Client' : 'Add New Client'}</h3>
                        {errorMessage && <p className={styles.errorMessage}>{errorMessage}</p>}
                        <form onSubmit={editId ? handleUpdateClient : handleAddClient} className={styles.form}>
                            <input type="text" name="name" value={formData.name} onChange={handleChange} placeholder="Nom" required />
                            <input type="email" name="email" value={formData.email} onChange={handleChange} placeholder="Email" required />
                            <input type="text" name="phone" value={formData.phone} onChange={handleChange} placeholder="Téléphone" />
                            <input type="text" name="company_name" value={formData.company_name} onChange={handleChange} placeholder="Nom de la société" />
                            <input type="text" name="address" value={formData.address} onChange={handleChange} placeholder="Adresse" />
                            <input type="text" name="city" value={formData.city} onChange={handleChange} placeholder="Ville" />
                            <input type="text" name="postal_code" value={formData.postal_code} onChange={handleChange} placeholder="Code postal" />
                            <input type="text" name="country" value={formData.country} onChange={handleChange} placeholder="Pays" />
                            <textarea name="notes" value={formData.notes} onChange={handleChange} placeholder="Notes supplémentaires" />

                            <div className={styles.buttonContainer}>
                                <button type="submit" className={styles.button}>{editId ? 'Update' : 'Add'} Client</button>
                                <button type="button" onClick={clearForm} className={styles.button}>Clear</button>
                                <button type="button" onClick={closeForm} className={`${styles.button} ${styles.cancel}`}>Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default GfClients;
