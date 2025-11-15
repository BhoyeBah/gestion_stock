export const SEARCH_INDEX = [
  {
    "name": "home",
    "title": "Accueil – Tableau Général",
    "uri": "http://gstock.dymotechnologie.com/",
    "uri_local": "/",
    "method": "GET",
    "tags": ["accueil", "home", "principal", "tableau", "dashboard", "statistiques", "analyse rapide", "overview"],
    "icon": null
  },
  {
    "name": "dashboard",
    "title": "Dashboard – Vue d’Ensemble",
    "uri": "http://gstock.dymotechnologie.com/dashboard",
    "uri_local": "/dashboard",
    "method": "GET",
    "tags": ["dashboard", "tableau de bord", "statistiques", "résumé", "analytics", "gestion", "overview"],
    "icon": null
  },
  {
    "name": "roles.index",
    "title": "Gestion des Rôles & Permissions",
    "uri": "http://gstock.dymotechnologie.com/roles",
    "uri_local": "/roles",
    "method": "GET",
    "tags": ["rôles", "permissions", "sécurité", "administration", "gestion utilisateurs", "droits", "autorisation", "accès"],
    "icon": null
  },
  {
    "name": "users.index",
    "title": "Gestion des Utilisateurs",
    "uri": "http://gstock.dymotechnologie.com/users",
    "uri_local": "/users",
    "method": "GET",
    "tags": ["utilisateurs", "users", "comptes", "équipe", "staff", "gestion utilisateurs", "administration"],
    "icon": null
  },
  {
    "name": "tenant.subscriptions.index",
    "title": "Mes Abonnements – Espace Client",
    "uri_local": "/tenant/subscriptions",
    "method": "GET",
    "tags": ["abonnements", "tenant", "facturation", "paiement", "plan", "forfait", "renouvellement"],
    "icon": null
  },
  {
    "name": "user.activity.index",
    "title": "Journal d’Activité – Historique des Actions",
    "uri": "http://gstock.dymotechnologie.com/activities",
    "uri_local": "/activities",
    "method": "GET",
    "tags": ["activités", "logs", "historique", "tracking", "audit", "journal d'activité", "sécurité"],
    "icon": null
  },
  {
    "name": "categories.index",
    "title": "Gestion des Catégories Produits",
    "uri": "http://gstock.dymotechnologie.com/categories",
    "uri_local": "/categories",
    "method": "GET",
    "tags": ["catégories", "produits", "organisation", "classification", "stock", "inventaire"],
    "icon": null
  },
  {
    "name": "products.index",
    "title": "Catalogue des Produits",
    "uri": "http://gstock.dymotechnologie.com/products",
    "uri_local": "/products",
    "method": "GET",
    "tags": ["produits", "inventaire", "articles", "stock", "gestion produits", "marchandises"],
    "icon": null
  },
  {
    "name": "warehouses.index",
    "title": "Gestion des Entrepôts & Stocks",
    "uri": "http://gstock.dymotechnologie.com/warehouses",
    "uri_local": "/warehouses",
    "method": "GET",
    "tags": ["entrepôts", "magasins", "stockage", "stock", "inventaire", "logistique", "dépôt"],
    "icon": null
  },
  {
    "name": "settings.index",
    "title": "Paramètres Généraux du Système",
    "uri": "http://gstock.dymotechnologie.com/settings",
    "uri_local": "/settings",
    "method": "GET",
    "tags": ["paramètres", "configuration", "réglages", "système", "administration", "options"],
    "icon": null
  },
  {
    "name": "profile.edit",
    "title": "Modifier mon Profil",
    "uri": "http://gstock.dymotechnologie.com/profile",
    "uri_local": "/profile",
    "method": "GET",
    "tags": ["profil", "compte", "utilisateur", "paramètres", "informations personnelles"],
    "icon": null
  },
  {
    "name": "clients.index",
    "title": "Liste et Gestion des Clients",
    "uri": "http://gstock.dymotechnologie.com/clients",
    "uri_local": "/clients",
    "method": "GET",
    "tags": ["clients", "contacts", "crm", "gestion clients", "ventes", "commercial"],
    "icon": null
  },
  {
    "name": "suppliers.index",
    "title": "Liste et Gestion des Fournisseurs",
    "uri": "http://gstock.dymotechnologie.com/suppliers",
    "uri_local": "/suppliers",
    "method": "GET",
    "tags": ["fournisseurs", "contacts", "achats", "approvisionnement", "gestion fournisseur"],
    "icon": null
  },
  {
    "name": "invoices.index.clients",
    "title": "Factures Clients – Ventes",
    "uri": "http://gstock.dymotechnologie.com/invoices/clients",
    "uri_local": "/invoices/clients",
    "method": "GET",
    "tags": ["factures clients", "ventes", "comptabilité", "paiements", "facturation", "documents"],
    "icon": null
  },
  {
    "name": "invoices.index.suppliers",
    "title": "Factures Fournisseurs – Achats",
    "uri": "http://gstock.dymotechnologie.com/invoices/suppliers",
    "uri_local": "/invoices/suppliers",
    "method": "GET",
    "tags": ["factures fournisseurs", "achats", "comptabilité", "dépenses", "fournisseurs"],
    "icon": null
  },

  // ------------------------------
  // 🚀 NOUVELLES ROUTES STATISTIQUES
  // ------------------------------

  {
    "name": "reports.index",
    "title": "Statistiques & Rapports – Vue Globale",
    "uri": "http://gstock.dymotechnologie.com/reports",
    "uri_local": "/reports",
    "method": "GET",
    "tags": ["statistiques", "rapports", "analytics", "tableau de bord", "performances", "analyse", "ventes", "inventaire", "activité"],
    "icon": null
  },
  {
    "name": "reports.journal",
    "title": "Journal des Mouvements & Activités",
    "uri": "http://gstock.dymotechnologie.com/reports/journal",
    "uri_local": "/reports/journal",
    "method": "GET",
    "tags": ["journal", "mouvements", "transactions", "historique", "logs", "statistiques détaillées", "audit", "activité"],
    "icon": null
  },

  // ------------------------------

  {
    "name": "payments.index.clients",
    "title": "Paiements Clients – Encaissements",
    "uri": "http://gstock.dymotechnologie.com/payments/clients",
    "uri_local": "/payments/clients",
    "method": "GET",
    "tags": ["paiements clients", "encaissements", "factures", "comptabilité", "transactions", "ventes"],
    "icon": null
  },
  {
    "name": "payments.index.suppliers",
    "title": "Paiements Fournisseurs – Décaissements",
    "uri": "http://gstock.dymotechnologie.com/payments/suppliers",
    "uri_local": "/payments/suppliers",
    "method": "GET",
    "tags": ["paiements fournisseurs", "dépenses", "achats", "comptabilité", "transactions"],
    "icon": null
  },
  {
    "name": "expenses.index",
    "title": "Gestion des Dépenses",
    "uri": "http://gstock.dymotechnologie.com/expenses",
    "uri_local": "/expenses",
    "method": "GET",
    "tags": ["dépenses", "coûts", "charges", "frais", "comptabilité", "gestion dépenses"],
    "icon": null
  },
  {
    "name": "invoices.unpaid",
    "title": "Récouvrements",
    "uri": "http://gstock.dymotechnologie.com/invoices/clients/unpaid",
    "uri_local": "/invoices/clients/unpaid",
    "method": "GET",
    "tags": ["recouvrement", "récouvrement", "rapport", "impayée", "facture"],
    "icon": null
  }
];
