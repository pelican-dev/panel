import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { IconRefresh, IconPlayerPause, IconPlayerPlay, IconSwitchHorizontal, IconTrash } from "@tabler/icons-react";

export const ServerEditManageTab = () => {
    return (
        <div className="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Reinstall Server</CardTitle>
                    <CardDescription>This will reinstall the server with the currently assigned egg. All files will be deleted.</CardDescription>
                </CardHeader>
                <CardContent>
                    <Button variant="destructive" className="flex items-center gap-2">
                        <IconRefresh className="h-4 w-4" />
                        Reinstall Server
                    </Button>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Suspend Server</CardTitle>
                    <CardDescription>This will suspend the server, preventing it from being started.</CardDescription>
                </CardHeader>
                <CardContent className="flex gap-2">
                    <Button variant="secondary" className="flex items-center gap-2">
                        <IconPlayerPause className="h-4 w-4" />
                        Suspend Server
                    </Button>
                    <Button variant="secondary" className="flex items-center gap-2">
                        <IconPlayerPlay className="h-4 w-4" />
                        Unsuspend Server
                    </Button>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Transfer Server</CardTitle>
                    <CardDescription>This will transfer the server to a different node.</CardDescription>
                </CardHeader>
                <CardContent>
                    <Button variant="secondary" className="flex items-center gap-2">
                        <IconSwitchHorizontal className="h-4 w-4" />
                        Transfer Server
                    </Button>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Delete Server</CardTitle>
                    <CardDescription>This will permanently delete the server and all its files.</CardDescription>
                </CardHeader>
                <CardContent>
                    <Button variant="destructive" className="flex items-center gap-2">
                        <IconTrash className="h-4 w-4" />
                        Delete Server
                    </Button>
                </CardContent>
            </Card>
        </div>
    );
};
