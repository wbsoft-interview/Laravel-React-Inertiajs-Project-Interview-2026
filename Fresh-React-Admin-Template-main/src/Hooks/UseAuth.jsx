import { use } from 'react';
import { AuthContext } from '../ContextAPI/AuthContext';

const UseAuth = () => {
    const authInfo = use(AuthContext);
    return authInfo
};

export default UseAuth;