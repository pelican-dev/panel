import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { IconPencil, IconEye, IconLock, IconLockOpenOff } from "@tabler/icons-react";
import Link from "next/link";

const users = [
    {
        avatar: 'https://github.com/shadcn.png',
        username: 'johndoe',
        email: 'john.doe@example.com',
        use_totp: true,
        roles: ['Admin', 'User'],
        servers: 5,
        subusers: 2,
    },
    {
        avatar: 'https://github.com/shadcn.png',
        username: 'janedoe',
        email: 'jane.doe@example.com',
        use_totp: false,
        roles: ['User'],
        servers: 2,
        subusers: 0,
    },
];

export const UsersDataTable = () => {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Avatar</TableHead>
                    <TableHead>Username</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>2FA</TableHead>
                    <TableHead>Roles</TableHead>
                    <TableHead>Servers</TableHead>
                    <TableHead>Subusers</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {users.map((user) => (
                    <TableRow key={user.username}>
                        <TableCell>
                            <Avatar>
                                <AvatarImage src={user.avatar} />
                                <AvatarFallback>{user.username.charAt(0).toUpperCase()}</AvatarFallback>
                            </Avatar>
                        </TableCell>
                        <TableCell>{user.username}</TableCell>
                        <TableCell>{user.email}</TableCell>
                        <TableCell>{user.use_totp ? <IconLock className="h-5 w-5 text-green-500" /> : <IconLockOpenOff className="h-5 w-5 text-red-500" />}</TableCell>
                        <TableCell className="flex gap-1">
                            {user.roles.map((role) => (
                                <Badge key={role}>{role}</Badge>
                            ))}
                        </TableCell>
                        <TableCell>{user.servers}</TableCell>
                        <TableCell>{user.subusers}</TableCell>
                        <TableCell className="flex gap-2">
                            <Button variant="outline" size="icon"><IconEye className="h-4 w-4" /></Button>
                            <Button asChild variant="outline" size="icon"><Link href={`/admin/users/${user.username}/edit`}><IconPencil className="h-4 w-4" /></Link></Button>
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );
};
