import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Separator } from "@/components/ui/separator";

interface SettingsCardProps {
    title: string;
    description: string;
    children: React.ReactNode;
}

export const SettingsCard = ({ title, description, children }: SettingsCardProps) => {
    return (
        <Card>
            <CardHeader>
                <CardTitle>{title}</CardTitle>
                <CardDescription>{description}</CardDescription>
            </CardHeader>
            <Separator />
            <CardContent className="pt-6">
                {children}
            </CardContent>
        </Card>
    );
};
