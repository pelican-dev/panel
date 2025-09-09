import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Badge } from "@/components/ui/badge";
import { IconPlus, IconTrash, IconX, IconChevronDown, IconChevronUp } from "@tabler/icons-react";
import { useEffect, useState } from "react";

export interface EggVariable {
    name: string;
    description: string;
    env_variable: string;
    default_value: string;
    user_viewable: boolean;
    user_editable: boolean;
    rules: string[];
}

interface EggVariablesTabProps {
    variables: EggVariable[];
    onVariablesChange: (newVariables: EggVariable[]) => void;
}

export const EggVariablesTab = ({ variables, onVariablesChange }: EggVariablesTabProps) => {
    const [collapsed, setCollapsed] = useState<boolean[]>(() => variables.map(() => false));

    useEffect(() => {
        // Ensure collapsed state matches variables length
        setCollapsed((prev) => {
            if (prev.length === variables.length) return prev;
            const next = [...prev];
            if (variables.length > prev.length) {
                // add new entries as expanded (false)
                while (next.length < variables.length) next.push(false);
            } else {
                next.length = variables.length;
            }
            return next;
        });
    }, [variables.length]);

    const addVariable = () => {
        onVariablesChange([
            ...variables,
            {
                name: '',
                description: '',
                env_variable: '',
                default_value: '',
                user_viewable: true,
                user_editable: true,
                rules: ['required', 'string', 'max:255'],
            },
        ]);
        setCollapsed((prev) => [...prev, false]);
    };

    const handleRuleDragStart = (e: React.DragEvent, variableIndex: number, ruleIndex: number) => {
        e.dataTransfer.setData('text/plain', JSON.stringify({ variableIndex, ruleIndex }));
        e.dataTransfer.effectAllowed = 'move';
    };

    const handleRuleDragOver = (e: React.DragEvent) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    };

    const handleRuleDrop = (e: React.DragEvent, targetVariableIndex: number, targetRuleIndex: number) => {
        e.preventDefault();
        const dragData = JSON.parse(e.dataTransfer.getData('text/plain'));
        const { variableIndex: sourceVariableIndex, ruleIndex: sourceRuleIndex } = dragData;

        if (sourceVariableIndex !== targetVariableIndex) return; // Only allow reordering within same variable

        const variable = variables[sourceVariableIndex];
        const newRules = [...variable.rules];
        const [draggedRule] = newRules.splice(sourceRuleIndex, 1);
        newRules.splice(targetRuleIndex, 0, draggedRule);

        handleVariableChange(sourceVariableIndex, { rules: newRules });
    };

    const removeVariable = (index: number) => {
        onVariablesChange(variables.filter((_, i) => i !== index));
        setCollapsed((prev) => prev.filter((_, i) => i !== index));
    };

    const handleVariableChange = (index: number, updatedValues: Partial<EggVariable>) => {
        const newVariables = variables.map((variable, i) => 
            i === index ? { ...variable, ...updatedValues } : variable
        );
        onVariablesChange(newVariables);
    };

    return (
        <div className="space-y-4">
            <div className="flex items-center justify-between">
                <div className="text-sm text-muted-foreground">{variables.length} variable{variables.length === 1 ? '' : 's'}</div>
                <div className="flex items-center gap-2">
                    <Button type="button" variant="ghost" size="sm" onClick={() => setCollapsed(variables.map(() => true))}>Collapse all</Button>
                    <Button type="button" variant="ghost" size="sm" onClick={() => setCollapsed(variables.map(() => false))}>Expand all</Button>
                </div>
            </div>
            <div className="columns-1 md:columns-2 gap-4 [column-fill:_balance]">
            {variables.map((variable, index) => (
                <Card key={index} className="mb-4 break-inside-avoid">
                    <CardHeader className="flex flex-row items-center justify-between py-3">
                        <div className="flex items-center gap-2">
                            <button
                                type="button"
                                aria-label={collapsed[index] ? 'Expand' : 'Collapse'}
                                className="inline-flex h-8 w-8 items-center justify-center rounded hover:bg-muted/50"
                                onClick={() => setCollapsed((prev) => prev.map((v, i) => (i === index ? !v : v)))}
                            >
                                {collapsed[index] ? <IconChevronDown className="h-4 w-4" /> : <IconChevronUp className="h-4 w-4" />}
                            </button>
                            <CardTitle>{variable.name?.trim() ? variable.name : 'New Variable'}</CardTitle>
                        </div>
                        <Button aria-label="Delete variable" variant="destructive" size="icon" onClick={() => removeVariable(index)}>
                            <IconTrash className="h-4 w-4" />
                        </Button>
                    </CardHeader>
                    {!collapsed[index] && (
                    <CardContent className="space-y-4">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div className="space-y-2">
                                <Label htmlFor={`name-${index}`}>Name</Label>
                                <Input id={`name-${index}`} value={variable.name} onChange={(e) => handleVariableChange(index, { name: e.target.value })} className="bg-muted" />
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor={`env_variable-${index}`}>Environment Variable</Label>
                                <Input id={`env_variable-${index}`} value={variable.env_variable} onChange={(e) => handleVariableChange(index, { env_variable: e.target.value })} className="bg-muted" />
                            </div>
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor={`description-${index}`}>Description</Label>
                            <Input id={`description-${index}`} value={variable.description} onChange={(e) => handleVariableChange(index, { description: e.target.value })} className="bg-muted" />
                        </div>
                        <div className="space-y-2">
                            <Label htmlFor={`default_value-${index}`}>Default Value</Label>
                            <Input id={`default_value-${index}`} value={variable.default_value} onChange={(e) => handleVariableChange(index, { default_value: e.target.value })} className="bg-muted" />
                        </div>
                        <div className="space-y-2">
                            <Label>Rules</Label>
                            <div className="space-y-2">
                                <div className="flex flex-wrap gap-2 min-h-[40px] p-2 border rounded-md bg-background">
                                    {variable.rules.map((rule, ruleIndex) => (
                                        <Badge 
                                            key={ruleIndex} 
                                            variant="secondary" 
                                            className="flex items-center gap-1 cursor-move select-none"
                                            draggable
                                            onDragStart={(e) => handleRuleDragStart(e, index, ruleIndex)}
                                            onDragOver={handleRuleDragOver}
                                            onDrop={(e) => handleRuleDrop(e, index, ruleIndex)}
                                        >
                                            {rule}
                                            <IconX 
                                                className="h-3 w-3 cursor-pointer hover:text-destructive" 
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    const newRules = variable.rules.filter((_, i) => i !== ruleIndex);
                                                    handleVariableChange(index, { rules: newRules });
                                                }}
                                            />
                                        </Badge>
                                    ))}
                                    {variable.rules.length === 0 && (
                                        <span className="text-muted-foreground text-sm">No rules</span>
                                    )}
                                </div>
                                <div className="flex gap-2">
                                    <Input 
                                        placeholder="New rule" 
                                        className="bg-muted"
                                        onKeyDown={(e) => {
                                            if (e.key === 'Enter' && e.currentTarget.value.trim()) {
                                                const newRule = e.currentTarget.value.trim();
                                                handleVariableChange(index, { rules: [...variable.rules, newRule] });
                                                e.currentTarget.value = '';
                                            }
                                        }}
                                    />
                                    <Button 
                                        type="button"
                                        variant="outline" 
                                        size="icon"
                                        onClick={(e) => {
                                            const input = e.currentTarget.previousElementSibling as HTMLInputElement;
                                            if (input?.value.trim()) {
                                                const newRule = input.value.trim();
                                                handleVariableChange(index, { rules: [...variable.rules, newRule] });
                                                input.value = '';
                                            }
                                        }}
                                    >
                                        <IconPlus className="h-4 w-4" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <div className="flex gap-4">
                            <div className="flex items-center space-x-2">
                                <Checkbox id={`user_viewable-${index}`} checked={variable.user_viewable} onCheckedChange={(c) => handleVariableChange(index, { user_viewable: !!c.valueOf() })} />
                                <Label htmlFor={`user_viewable-${index}`}>User Viewable</Label>
                            </div>
                            <div className="flex items-center space-x-2">
                                <Checkbox id={`user_editable-${index}`} checked={variable.user_editable} onCheckedChange={(c) => handleVariableChange(index, { user_editable: !!c.valueOf() })} />
                                <Label htmlFor={`user_editable-${index}`}>User Editable</Label>
                            </div>
                        </div>
                    </CardContent>
                    )}
                </Card>
            ))}
            </div>
            <Button variant="outline" onClick={addVariable} className="flex items-center gap-2">
                <IconPlus className="h-4 w-4" />
                Add Variable
            </Button>
        </div>
    );
};
