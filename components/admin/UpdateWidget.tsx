import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { IconCheck, IconInfoCircle, IconClipboardText } from '@tabler/icons-react';

export const UpdateWidget = () => {
    // In a real application, this would come from an API call
    const isLatest = false;
    const currentVersion = "v1.0.0";
    const latestVersion = "v1.1.0";

    if (isLatest) {
        return (
            <Alert variant="default" className="bg-green-100 dark:bg-green-900/30 border-green-200 dark:border-green-800">
                <IconCheck className="h-5 w-5 text-green-600 dark:text-green-400" />
                <AlertTitle className="text-green-800 dark:text-green-200">You&apos;re up to date!</AlertTitle>
                <AlertDescription className="text-green-700 dark:text-green-300">
                    You are running the latest version of Pelican Panel ({currentVersion}).
                </AlertDescription>
            </Alert>
        );
    }

    return (
        <Alert variant="destructive" className="bg-yellow-100 dark:bg-yellow-900/30 border-yellow-200 dark:border-yellow-800 flex items-center justify-between">
            <div className="flex items-center">
                <IconInfoCircle className="h-5 w-5 text-yellow-600 dark:text-yellow-400 mr-3" />
                <div>
                    <AlertTitle className="text-yellow-800 dark:text-yellow-200">Update Available</AlertTitle>
                    <AlertDescription className="text-yellow-700 dark:text-yellow-300">
                        A new version ({latestVersion}) is available. Please update to get the latest features and security fixes.
                    </AlertDescription>
                </div>
            </div>
            <Button asChild variant="outline" size="sm">
                <a href="https://pelican.dev/docs/panel/update" target="_blank" rel="noopener noreferrer" className="flex items-center gap-2">
                    <IconClipboardText className="h-4 w-4" />
                    View Update Guide
                </a>
            </Button>
        </Alert>
    );
};
