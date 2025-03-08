export function generateAvatarPlaceholder(name) {
    let names = name?.split(' ');
    let placeholder = '';

    // A placeholder should have a maximum of 2 characters
    for (let i = 0; i < names?.length && i < 2; i++) {
        placeholder += names[i].charAt(0);
    }

    return placeholder.toUpperCase();
}
