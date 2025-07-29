# Panduan Migrasi ke React.js - Laundry Fezz

## Mengapa Migrate ke React.js?

### Keunggulan React.js Implementation
1. **Single Page Application (SPA)**: Loading yang lebih cepat dan smooth user experience
2. **Component-Based Architecture**: Code yang lebih modular dan reusable
3. **State Management**: Dengan Redux/Context API untuk state yang complex
4. **Modern Development**: Hot reload, modern JavaScript features (ES6+)
5. **Better Performance**: Virtual DOM untuk rendering yang lebih efisien
6. **Mobile Ready**: Mudah dikembangkan ke React Native untuk mobile app
7. **SEO Friendly**: Dengan Next.js untuk server-side rendering
8. **Rich Ecosystem**: Banyak libraries dan tools yang tersedia

## Roadmap Migrasi

### Phase 1: Setup React Environment (1-2 minggu)

#### 1.1 Setup Development Environment
```bash
# Install Node.js dan npm (jika belum ada)
# Download dari https://nodejs.org/

# Verify installation
node --version
npm --version

# Install Create React App
npm install -g create-react-app
```

#### 1.2 Create React Project Structure
```bash
# Create new React app
npx create-react-app laundry-fezz-react
cd laundry-fezz-react

# Install additional dependencies
npm install axios react-router-dom redux react-redux @reduxjs/toolkit
npm install bootstrap react-bootstrap @fortawesome/react-fontawesome
npm install datatables.net-bs5 datatables.net-react
npm install react-toastify react-hook-form
```

#### 1.3 Project Structure
```
laundry-fezz-react/
├── public/
├── src/
│   ├── components/          # Reusable components
│   │   ├── common/         # Common components (Navbar, Footer, etc.)
│   │   ├── forms/          # Form components
│   │   └── tables/         # Table components
│   ├── pages/              # Page components
│   │   ├── auth/           # Login, Register
│   │   ├── dashboard/      # Dashboard
│   │   ├── transactions/   # Transaction management
│   │   ├── packages/       # Package management
│   │   └── profile/        # User profile
│   ├── services/           # API services
│   ├── store/              # Redux store
│   ├── utils/              # Utility functions
│   ├── styles/             # CSS/SCSS files
│   └── App.js
├── package.json
└── README.md
```

### Phase 2: API Development (2-3 minggu)

#### 2.1 Convert PHP to REST API
Buat folder `api/` dalam project PHP yang sudah ada:

```php
// api/config.php
<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include '../routes/config.php';
$db = new Database();
?>
```

#### 2.2 Authentication API
```php
// api/auth/login.php
<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'];
    $password = $input['password'];
    
    $user_data = $db->login($username);
    
    if (!empty($user_data)) {
        $user = $user_data[0];
        if ($password === $user['password']) {
            // Generate JWT token (implement JWT library)
            $token = generateJWTToken($user);
            
            echo json_encode([
                'success' => true,
                'token' => $token,
                'user' => [
                    'username' => $user['username'],
                    'nama_pelanggan' => $user['nama_pelanggan'],
                    'akses_id' => $user['akses_id'],
                    'kode_pelanggan' => $user['kode_pelanggan']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
}
?>
```

#### 2.3 Transactions API
```php
// api/transactions/index.php
<?php
include '../config.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $transaksi = $db->tampil_transaksi();
        echo json_encode(['success' => true, 'data' => $transaksi]);
        break;
        
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $db->tambah_transaksi(
            $input['kode_transaksi'],
            $input['kode_pelanggan'],
            $input['kode_paket'],
            $input['harga'],
            $input['kilo'],
            $input['total'],
            $input['tanggal_masuk'],
            $input['tanggal_keluar'],
            $input['status']
        );
        echo json_encode(['success' => $result]);
        break;
        
    case 'PUT':
        // Handle update
        break;
        
    case 'DELETE':
        // Handle delete
        break;
}
?>
```

### Phase 3: React Components Development (3-4 minggu)

#### 3.1 Authentication Components
```jsx
// src/components/auth/LoginForm.jsx
import React, { useState } from 'react';
import { useDispatch } from 'react-redux';
import { login } from '../../store/authSlice';

const LoginForm = () => {
    const [credentials, setCredentials] = useState({
        username: '',
        password: ''
    });
    const dispatch = useDispatch();

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await dispatch(login(credentials)).unwrap();
            // Redirect to dashboard
        } catch (error) {
            // Handle error
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            {/* Form JSX */}
        </form>
    );
};

export default LoginForm;
```

#### 3.2 Dashboard Components
```jsx
// src/pages/dashboard/Dashboard.jsx
import React, { useEffect, useState } from 'react';
import { Card, Row, Col } from 'react-bootstrap';
import StatCard from '../../components/common/StatCard';
import TransactionTable from '../../components/tables/TransactionTable';
import { getTransactions, getStats } from '../../services/api';

const Dashboard = () => {
    const [stats, setStats] = useState({});
    const [recentTransactions, setRecentTransactions] = useState([]);

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const [statsData, transactionsData] = await Promise.all([
                getStats(),
                getTransactions({ limit: 5 })
            ]);
            setStats(statsData);
            setRecentTransactions(transactionsData);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };

    return (
        <div className="dashboard">
            <Row>
                <Col md={3}>
                    <StatCard
                        title="Total Pelanggan"
                        value={stats.totalPelanggan}
                        icon="users"
                        color="primary"
                    />
                </Col>
                {/* More stat cards */}
            </Row>
            
            <Card className="mt-4">
                <Card.Header>
                    <h5>Transaksi Terbaru</h5>
                </Card.Header>
                <Card.Body>
                    <TransactionTable
                        data={recentTransactions}
                        showActions={false}
                    />
                </Card.Body>
            </Card>
        </div>
    );
};

export default Dashboard;
```

#### 3.3 Data Management Components
```jsx
// src/components/tables/DataTable.jsx
import React, { useState, useEffect } from 'react';
import { Table, Button, Modal } from 'react-bootstrap';
import { toast } from 'react-toastify';

const DataTable = ({ 
    columns, 
    data, 
    onAdd, 
    onEdit, 
    onDelete,
    addButtonText = "Tambah Data"
}) => {
    const [showModal, setShowModal] = useState(false);
    const [selectedItem, setSelectedItem] = useState(null);

    const handleDelete = async (id) => {
        if (window.confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            try {
                await onDelete(id);
                toast.success('Data berhasil dihapus');
            } catch (error) {
                toast.error('Gagal menghapus data');
            }
        }
    };

    return (
        <div>
            <div className="d-flex justify-content-between mb-3">
                <h4>Data Management</h4>
                <Button variant="primary" onClick={() => setShowModal(true)}>
                    {addButtonText}
                </Button>
            </div>
            
            <Table striped bordered hover responsive>
                <thead>
                    <tr>
                        {columns.map((col, index) => (
                            <th key={index}>{col.header}</th>
                        ))}
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {data.map((item, index) => (
                        <tr key={index}>
                            {columns.map((col, colIndex) => (
                                <td key={colIndex}>
                                    {col.render ? col.render(item) : item[col.key]}
                                </td>
                            ))}
                            <td>
                                <Button
                                    variant="warning"
                                    size="sm"
                                    className="me-2"
                                    onClick={() => onEdit(item)}
                                >
                                    Edit
                                </Button>
                                <Button
                                    variant="danger"
                                    size="sm"
                                    onClick={() => handleDelete(item.id)}
                                >
                                    Hapus
                                </Button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </Table>
        </div>
    );
};

export default DataTable;
```

### Phase 4: State Management dengan Redux (1-2 minggu)

#### 4.1 Redux Store Setup
```javascript
// src/store/index.js
import { configureStore } from '@reduxjs/toolkit';
import authSlice from './authSlice';
import transactionSlice from './transactionSlice';
import packageSlice from './packageSlice';

export const store = configureStore({
    reducer: {
        auth: authSlice,
        transactions: transactionSlice,
        packages: packageSlice,
    },
});
```

#### 4.2 Auth Slice
```javascript
// src/store/authSlice.js
import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import { loginAPI, logoutAPI } from '../services/authService';

export const login = createAsyncThunk(
    'auth/login',
    async (credentials, { rejectWithValue }) => {
        try {
            const response = await loginAPI(credentials);
            localStorage.setItem('token', response.token);
            return response;
        } catch (error) {
            return rejectWithValue(error.message);
        }
    }
);

const authSlice = createSlice({
    name: 'auth',
    initialState: {
        user: null,
        token: localStorage.getItem('token'),
        isLoading: false,
        error: null
    },
    reducers: {
        logout: (state) => {
            state.user = null;
            state.token = null;
            localStorage.removeItem('token');
        }
    },
    extraReducers: (builder) => {
        builder
            .addCase(login.pending, (state) => {
                state.isLoading = true;
                state.error = null;
            })
            .addCase(login.fulfilled, (state, action) => {
                state.isLoading = false;
                state.user = action.payload.user;
                state.token = action.payload.token;
            })
            .addCase(login.rejected, (state, action) => {
                state.isLoading = false;
                state.error = action.payload;
            });
    }
});

export const { logout } = authSlice.actions;
export default authSlice.reducer;
```

### Phase 5: API Integration (1 minggu)

#### 5.1 API Service Layer
```javascript
// src/services/api.js
import axios from 'axios';

const API_BASE_URL = 'http://localhost/laundry-fezz/api';

const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

// Request interceptor untuk menambahkan token
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor untuk handle errors
api.interceptors.response.use(
    (response) => response.data,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export const transactionAPI = {
    getAll: () => api.get('/transactions'),
    create: (data) => api.post('/transactions', data),
    update: (id, data) => api.put(`/transactions/${id}`, data),
    delete: (id) => api.delete(`/transactions/${id}`)
};

export const packageAPI = {
    getAll: () => api.get('/packages'),
    create: (data) => api.post('/packages', data),
    update: (id, data) => api.put(`/packages/${id}`, data),
    delete: (id) => api.delete(`/packages/${id}`)
};

export default api;
```

### Phase 6: Testing & Deployment (1-2 minggu)

#### 6.1 Unit Testing
```javascript
// src/components/__tests__/LoginForm.test.js
import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { Provider } from 'react-redux';
import { store } from '../../store';
import LoginForm from '../auth/LoginForm';

test('renders login form', () => {
    render(
        <Provider store={store}>
            <LoginForm />
        </Provider>
    );
    
    expect(screen.getByLabelText(/username/i)).toBeInTheDocument();
    expect(screen.getByLabelText(/password/i)).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /login/i })).toBeInTheDocument();
});
```

#### 6.2 Build untuk Production
```bash
# Build React app
npm run build

# Deploy ke web server
# Copy build folder ke web server atau use hosting service
```

## Migration Strategy

### 1. Parallel Development
- Kembangkan React app secara paralel dengan PHP app yang sudah ada
- Gunakan subdomain atau path yang berbeda (misal: react.laundryfezz.com)
- Migrate fitur satu per satu

### 2. API-First Approach
- Convert PHP ke REST API terlebih dahulu
- Test API dengan tools seperti Postman
- Kemudian develop React frontend

### 3. Progressive Migration
- Mulai dengan halaman yang paling simple (Dashboard)
- Lanjut ke CRUD operations (Packages, Users)
- Terakhir fitur complex (Reports, Analytics)

## Benefits After Migration

### Developer Experience
- Hot reload untuk development yang lebih cepat
- Better debugging tools (React DevTools)
- Modern JavaScript features (ES6+, async/await)
- Component-based development

### User Experience
- Faster page transitions (SPA)
- Better responsiveness
- Real-time updates dengan WebSocket
- Offline capabilities dengan Service Workers

### Maintenance & Scalability
- Modular code structure
- Easier testing
- Better separation of concerns
- Scalable architecture

## Estimated Timeline
- **Total Duration**: 8-12 minggu
- **Team Size**: 2-3 developers
- **Effort**: Part-time development

## Resources Needed
1. React.js documentation
2. Redux Toolkit documentation
3. Bootstrap React components
4. JWT implementation guide
5. Testing library documentation

Dengan mengikuti roadmap ini, Anda dapat melakukan migrasi sistematis dari PHP tradisional ke modern React.js application sambil mempertahankan semua functionality yang sudah ada.
