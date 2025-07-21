import React from 'react';
import { Drawer, List, ListItem, ListItemText, ListItemIcon } from '@mui/material';
import HomeIcon from '@mui/icons-material/Home';
import ListAltIcon from '@mui/icons-material/ListAlt';

function SideBar() {
    return (
        <Drawer variant="permanent" sx={{ width: 240, flexShrink: 0 }}>
            <List>
                <ListItem button>
                    <ListItemIcon><HomeIcon /></ListItemIcon>
                    <ListItemText primary="Accueil" />
                </ListItem>
                <ListItem button>
                    <ListItemIcon><ListAltIcon /></ListItemIcon>
                    <ListItemText primary="Liste des Factures" />
                </ListItem>
                {/* Ajoutez d'autres éléments ici */}
            </List>
        </Drawer>
    );
}

export default SideBar;
