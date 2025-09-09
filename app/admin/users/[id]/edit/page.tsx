'use client';

import { useState } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from "@/components/ui/checkbox";
import { Button } from "@/components/ui/button";

const roles = [
    {
        id: 'admin',
        label: 'Administrator',
    },
    {
        id: 'support',
        label: 'Support',
    },
    {
        id: 'user',
        label: 'User',
    },
];

// This is a mock user. In a real application, you would fetch this data based on the id.
const mockUser = {
    id: '1',
    username: 'johndoe',
    email: 'john.doe@example.com',
    roles: ['user'],
};

export default function EditUserPage() {
    const [username, setUsername] = useState(mockUser.username);
    const [email, setEmail] = useState(mockUser.email);
    const [password, setPassword] = useState('');
    const [selectedRoles, setSelectedRoles] = useState(mockUser.roles);

    const handleRoleChange = (roleId: string) => {
        setSelectedRoles(prev => 
            prev.includes(roleId) 
                ? prev.filter(id => id !== roleId) 
                : [...prev, roleId]
        );
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        console.log({
            username,
            email,
            password: password || 'unchanged',
            roles: selectedRoles,
        });
        // Here you would typically make an API call to update the user
    };

    return (
        <div>
            <h1 className="text-3xl font-bold mb-6">Edit User: {mockUser.username}</h1>
            <form onSubmit={handleSubmit}>
                <Card>
                    <CardHeader>
                        <CardTitle>User Details</CardTitle>
                        <CardDescription>Update the user&apos;s details below.</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="username">Username</Label>
                                <Input id="username" value={username} onChange={(e) => setUsername(e.target.value)} required />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="email">Email</Label>
                                <Input id="email" type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="password">New Password</Label>
                            <Input id="password" type="password" placeholder="Leave blank to keep current password" value={password} onChange={(e) => setPassword(e.target.value)} />
                        </div>
                        <div className="space-y-2">
                            <Label>Roles</Label>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {roles.map((role) => (
                                    <div key={role.id} className="flex items-center space-x-2">
                                        <Checkbox id={role.id} checked={selectedRoles.includes(role.id)} onCheckedChange={() => handleRoleChange(role.id)} />
                                        <Label htmlFor={role.id}>{role.label}</Label>
                                    </div>
                                ))}
                            </div>
                        </div>
                        <div className="flex justify-end gap-2">
                            <Button type="button" variant="outline">Cancel</Button>
                            <Button type="submit">Save Changes</Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    );
}
