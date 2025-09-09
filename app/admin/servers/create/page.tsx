'use client';

import { useState } from 'react';
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { IconInfoCircle, IconEgg, IconBrandDocker, IconArrowRight, IconArrowLeft } from '@tabler/icons-react';

import { ServerInformationStep } from "@/components/admin/forms/ServerInformationStep";
import { ServerEggConfigurationStep } from "@/components/admin/forms/ServerEggConfigurationStep";
import { ServerEnvironmentStep } from "@/components/admin/forms/ServerEnvironmentStep";

const steps = [
    { id: 'information', name: 'Information', icon: IconInfoCircle, component: ServerInformationStep },
    { id: 'egg_configuration', name: 'Egg Configuration', icon: IconEgg, component: ServerEggConfigurationStep },
    { id: 'environment', name: 'Environment', icon: IconBrandDocker, component: ServerEnvironmentStep },
];

export default function CreateServerPage() {
    const [currentStep, setCurrentStep] = useState(0);

    const nextStep = () => {
        if (currentStep < steps.length - 1) {
            setCurrentStep(currentStep + 1);
        }
    };

    const prevStep = () => {
        if (currentStep > 0) {
            setCurrentStep(currentStep - 1);
        }
    };

    const ActiveStepComponent = steps[currentStep].component;

    return (
        <div>
            <h1 className="text-3xl font-bold mb-6">Create Server</h1>
            <div className="flex items-center justify-center mb-6">
                {steps.map((step, index) => (
                    <div key={step.id} className="flex items-center">
                        <div className={`flex flex-col items-center ${index <= currentStep ? 'text-primary' : 'text-muted-foreground'}`}>
                            <div className={`w-10 h-10 rounded-full flex items-center justify-center border-2 ${index <= currentStep ? 'border-primary' : ''}`}>
                                <step.icon className="h-6 w-6" />
                            </div>
                            <p className="text-sm mt-2">{step.name}</p>
                        </div>
                        {index < steps.length - 1 && (
                            <div className={`flex-auto border-t-2 mx-4 ${index < currentStep ? 'border-primary' : 'border'}`}></div>
                        )}
                    </div>
                ))}
            </div>
            <Card>
                <CardContent className="pt-6">
                    <ActiveStepComponent />
                </CardContent>
            </Card>
            <div className="flex justify-between mt-6">
                <Button variant="outline" onClick={prevStep} disabled={currentStep === 0} className="flex items-center gap-2">
                    <IconArrowLeft className="h-4 w-4" />
                    Previous
                </Button>
                {currentStep < steps.length - 1 ? (
                    <Button onClick={nextStep} className="flex items-center gap-2">
                        Next
                        <IconArrowRight className="h-4 w-4" />
                    </Button>
                ) : (
                    <Button>Create Server</Button>
                )}
            </div>
        </div>
    );
}
