import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { IconCode, IconBrandGithub } from '@tabler/icons-react';

export const CanaryWidget = () => {
    // In a real application, this would come from config
    const isCanary = true;

    if (!isCanary) {
        return null;
    }

    return (
        <Card>
            <CardHeader className="flex flex-row items-center justify-between">
                <div className="flex items-center gap-3">
                    <IconCode className="h-6 w-6" />
                    <div>
                        <CardTitle>For Developers</CardTitle>
                        <CardDescription>You are running a canary build. This is not recommended for production environments.</CardDescription>
                    </div>
                </div>
                <Button asChild variant="outline" size="sm">
                    <a href="https://github.com/pelican-dev/panel/issues" target="_blank" rel="noopener noreferrer" className="flex items-center gap-2">
                        <IconBrandGithub className="h-4 w-4" />
                        Report an Issue
                    </a>
                </Button>
            </CardHeader>
            <CardContent>
                <p className="text-sm text-muted-foreground">
                    This is a pre-release version of Pelican Panel. It may contain bugs or incomplete features. Please report any issues you find to our GitHub repository.
                </p>
            </CardContent>
        </Card>
    );
};
