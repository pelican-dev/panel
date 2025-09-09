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

export default function CreateUserPage() {
    const [username, setUsername] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [selectedRoles, setSelectedRoles] = useState<string[]>([]);

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
            password,
            roles: selectedRoles,
        });
        // Here you would typically make an API call to create the user
    };

    return (
        <div>
            <h1 className="text-3xl font-bold mb-6">Create User</h1>
            <form onSubmit={handleSubmit}>
                <Card>
                    <CardHeader>
                        <CardTitle>User Details</CardTitle>
                        <CardDescription>Fill out the form below to create a new user.</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor="username">Username</Label>
                                <Input id="username" placeholder="johndoe" value={username} onChange={(e) => setUsername(e.target.value)} required />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="email">Email</Label>
                                <Input id="email" type="email" placeholder="john.doe@example.com" value={email} onChange={(e) => setEmail(e.target.value)} required />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor="password">Password</Label>
                            <Input id="password" type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
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
                            <Button type="submit">Create User</Button>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    );
}
